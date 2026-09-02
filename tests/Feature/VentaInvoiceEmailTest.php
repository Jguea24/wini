<?php

namespace Tests\Feature;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Factura;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class VentaInvoiceEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_registering_sale_creates_invoice_and_queues_invoice_email(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('ventas.store'), [
                'fecha' => now()->toDateString(),
                'cliente_nombre' => 'Cliente Prueba',
                'cliente_empresa' => 'Empresa Prueba',
                'cliente_identificacion' => null,
                'cliente_telefono' => '0999999999',
                'cliente_direccion' => 'Direccion de prueba',
                'cliente_correo' => 'cliente@example.com',
                'libras' => '10',
                'precio_por_libra' => '2.50',
                'metodo_pago' => 'efectivo',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('ventas.index'));

        $venta = Venta::query()->firstOrFail();
        $factura = Factura::query()->where('venta_id', $venta->id)->firstOrFail();

        Queue::assertPushed(SendInvoiceEmailJob::class, function (SendInvoiceEmailJob $job) use ($factura) {
            return $job->facturaId === $factura->id;
        });
    }

    public function test_manually_sending_invoice_email_dispatches_job(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $cliente = \App\Models\Cliente::create([
            'nombre' => 'Cliente Test',
            'empresa' => 'Empresa Test',
            'telefono' => '0999999999',
            'correo' => 'cliente@example.com',
        ]);
        $venta = Venta::create([
            'cliente_id' => $cliente->id,
            'user_id' => $user->id,
            'created_by' => $user->id,
            'fecha' => now()->toDateString(),
            'libras' => 10,
            'precio_por_libra' => 2.50,
            'total' => 25.00,
            'metodo_pago' => 'efectivo',
        ]);
        $factura = Factura::create([
            'venta_id' => $venta->id,
            'user_id' => $user->id,
            'numero' => 'FAC-2026-000099',
            'fecha_emision' => now()->toDateString(),
            'subtotal' => 25.00,
            'descuento' => 0,
            'impuesto' => 0,
            'total' => 25.00,
            'estado' => 'emitida',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('facturas.send-email', $factura));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        Queue::assertPushed(SendInvoiceEmailJob::class, function (SendInvoiceEmailJob $job) use ($factura) {
            return $job->facturaId === $factura->id;
        });
    }

    public function test_updating_sale_keeps_invoice_amounts_in_sync(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $cliente = \App\Models\Cliente::create([
            'nombre' => 'Cliente Test',
            'empresa' => 'Empresa Test',
            'telefono' => '0999999999',
            'correo' => 'cliente@example.com',
        ]);
        $venta = Venta::create([
            'cliente_id' => $cliente->id,
            'user_id' => $user->id,
            'created_by' => $user->id,
            'fecha' => now()->toDateString(),
            'libras' => 10,
            'precio_por_libra' => 2.50,
            'total' => 25.00,
            'metodo_pago' => 'efectivo',
        ]);
        $factura = Factura::create([
            'venta_id' => $venta->id,
            'user_id' => $user->id,
            'numero' => 'FAC-2026-000100',
            'fecha_emision' => now()->toDateString(),
            'subtotal' => 25.00,
            'descuento' => 0,
            'impuesto' => 0,
            'total' => 25.00,
            'estado' => 'emitida',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('ventas.update', $venta), [
                'fecha' => now()->toDateString(),
                'cliente_nombre' => 'Cliente Test',
                'cliente_empresa' => 'Empresa Test',
                'cliente_identificacion' => null,
                'cliente_telefono' => '0999999999',
                'cliente_direccion' => 'Direccion actualizada',
                'cliente_correo' => 'cliente@example.com',
                'libras' => '12',
                'precio_por_libra' => '3.00',
                'metodo_pago' => 'transferencia',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('ventas.index'));

        $this->assertSame('36.00', $venta->refresh()->total);
        $this->assertSame('36.00', $factura->refresh()->subtotal);
        $this->assertSame('36.00', $factura->total);
        $this->assertSame($user->id, $factura->updated_by);
    }

    public function test_updating_sale_reuses_existing_customer_when_name_company_already_exists(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $clienteOriginal = \App\Models\Cliente::create([
            'nombre' => 'Cliente Original',
            'empresa' => 'Empresa Original',
            'identificacion' => '1550011041001',
            'telefono' => '0999999999',
            'correo' => 'original@example.com',
        ]);
        $clienteExistente = \App\Models\Cliente::create([
            'nombre' => 'Wini',
            'empresa' => 'Comercial Noboa',
            'identificacion' => null,
            'telefono' => '0997133824',
            'correo' => 'jhonnygrefa6@gmail.com',
        ]);
        $venta = Venta::create([
            'cliente_id' => $clienteOriginal->id,
            'user_id' => $user->id,
            'created_by' => $user->id,
            'fecha' => now()->toDateString(),
            'libras' => 10,
            'precio_por_libra' => 2.50,
            'total' => 25.00,
            'metodo_pago' => 'efectivo',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('ventas.update', $venta), [
                'fecha' => now()->toDateString(),
                'cliente_nombre' => 'Wini',
                'cliente_empresa' => 'Comercial Noboa',
                'cliente_identificacion' => '1550011041001',
                'cliente_telefono' => '0997133824',
                'cliente_direccion' => 'Via Tena - Archidona',
                'cliente_correo' => 'jhonnygrefa6@gmail.com',
                'libras' => '10',
                'precio_por_libra' => '2.50',
                'metodo_pago' => 'efectivo',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('ventas.index'));

        $this->assertSame($clienteExistente->id, $venta->refresh()->cliente_id);
        $this->assertSame('1550011041001', $clienteExistente->refresh()->identificacion);
    }
}
