<?php

namespace App\Services;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function __construct(private readonly FacturaService $facturas)
    {
    }

    /**
     * @return array{venta: Venta, factura: Factura, factura_email_solicitado: bool}
     */
    public function registrar(array $data, User $user): array
    {
        $resultado = DB::transaction(function () use ($data, $user): array {
            $cliente = $this->guardarCliente($data, $user);
            $venta = Venta::create($this->ventaData($data, $cliente, $user));
            $factura = $this->facturas->createForVenta($venta, $user);

            return compact('venta', 'factura');
        });

        $resultado['factura_email_solicitado'] = $this->solicitarEnvioFactura($resultado['factura']);

        return $resultado;
    }

    public function actualizar(Venta $venta, array $data, User $user): Venta
    {
        return DB::transaction(function () use ($venta, $data, $user): Venta {
            $cliente = $this->guardarCliente($data, $user);

            $venta->update($this->ventaData($data, $cliente, $user, true));
            $this->facturas->syncAmountsForVenta($venta, $user);

            return $venta->refresh();
        });
    }

    private function guardarCliente(array $data, User $user): Cliente
    {
        $cliente = $this->resolverCliente($data);

        if ($cliente->trashed()) {
            $cliente->restore();
        }

        $cliente->fill([
            'nombre' => $data['cliente_nombre'],
            'empresa' => $data['cliente_empresa'],
            'identificacion' => $data['cliente_identificacion'],
            'telefono' => $data['cliente_telefono'],
            'direccion' => $data['cliente_direccion'],
            'correo' => $data['cliente_correo'],
            'updated_by' => $user->id,
        ]);

        if (! $cliente->exists) {
            $cliente->created_by = $user->id;
        }

        $cliente->save();

        return $cliente;
    }

    private function resolverCliente(array $data): Cliente
    {
        $clientePorIdentificacion = null;

        if ($data['cliente_identificacion']) {
            $clientePorIdentificacion = Cliente::withTrashed()
                ->where('identificacion', $data['cliente_identificacion'])
                ->first();
        }

        $clientePorNombreEmpresa = Cliente::withTrashed()
            ->where('nombre', $data['cliente_nombre'])
            ->where('empresa', $data['cliente_empresa'])
            ->first();

        if ($clientePorIdentificacion && $clientePorNombreEmpresa) {
            return $clientePorIdentificacion->is($clientePorNombreEmpresa)
                ? $clientePorIdentificacion
                : $clientePorNombreEmpresa;
        }

        return $clientePorIdentificacion
            ?? $clientePorNombreEmpresa
            ?? new Cliente();
    }

    private function ventaData(array $data, Cliente $cliente, User $user, bool $isUpdate = false): array
    {
        $ventaData = [
            'cliente_id' => $cliente->id,
            'fecha' => $data['fecha'],
            'libras' => $data['libras'],
            'precio_por_libra' => $data['precio_por_libra'],
            'total' => $this->calcularTotal($data['libras'], $data['precio_por_libra']),
            'metodo_pago' => $data['metodo_pago'],
        ];

        if ($isUpdate) {
            $ventaData['updated_by'] = $user->id;

            return $ventaData;
        }

        return $ventaData + [
            'user_id' => $user->id,
            'created_by' => $user->id,
        ];
    }

    private function calcularTotal(float|string $libras, float|string $precioPorLibra): float
    {
        return round((float) $libras * (float) $precioPorLibra, 2);
    }

    private function solicitarEnvioFactura(Factura $factura): bool
    {
        $factura->loadMissing('venta.cliente');

        if (! $factura->venta?->cliente?->correo) {
            return false;
        }

        SendInvoiceEmailJob::dispatch($factura->id);

        return true;
    }
}
