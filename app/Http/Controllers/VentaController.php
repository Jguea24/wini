<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\StoreVentaRequest;
use App\Http\Requests\Ventas\UpdateVentaRequest;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $ventas = Venta::with(['cliente', 'user'])
            ->betweenDates($filters['desde'] ?? null, $filters['hasta'] ?? null)
            ->latest('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.index', compact('ventas'));
    }

    public function create(): View
    {
        return view('ventas.create');
    }

    public function store(StoreVentaRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request): void {
                $data = $request->validated();
                $cliente = $this->findOrCreateCliente($data);

                Venta::create([
                    'cliente_id' => $cliente->id,
                    'user_id' => $request->user()->id,
                    'created_by' => $request->user()->id,
                    'fecha' => $data['fecha'],
                    'libras' => $data['libras'],
                    'precio_por_libra' => $data['precio_por_libra'],
                    'total' => round($data['libras'] * $data['precio_por_libra'], 2),
                    'metodo_pago' => $data['metodo_pago'],
                ]);
            });

            return redirect()->route('ventas.index')->with('status', 'Venta registrada correctamente.');
        } catch (\Throwable $exception) {
            Log::error('No se pudo registrar la venta.', ['exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo registrar la venta.'])->withInput();
        }
    }

    public function edit(Venta $venta): View
    {
        Gate::authorize('update', $venta);

        $venta->load('cliente');

        return view('ventas.edit', compact('venta'));
    }

    public function update(UpdateVentaRequest $request, Venta $venta): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $venta): void {
                $data = $request->validated();
                $cliente = $this->findOrCreateCliente($data);

                $venta->update([
                    'cliente_id' => $cliente->id,
                    'updated_by' => $request->user()->id,
                    'fecha' => $data['fecha'],
                    'libras' => $data['libras'],
                    'precio_por_libra' => $data['precio_por_libra'],
                    'total' => round($data['libras'] * $data['precio_por_libra'], 2),
                    'metodo_pago' => $data['metodo_pago'],
                ]);
            });

            return redirect()->route('ventas.index')->with('status', 'Venta actualizada correctamente.');
        } catch (\Throwable $exception) {
            Log::error('No se pudo actualizar la venta.', ['venta_id' => $venta->id, 'exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo actualizar la venta.'])->withInput();
        }
    }

    public function destroy(Venta $venta): RedirectResponse
    {
        Gate::authorize('delete', $venta);

        $venta->update(['deleted_by' => auth()->id()]);
        $venta->delete();

        return redirect()->route('ventas.index')->with('status', 'Venta eliminada correctamente.');
    }

    private function findOrCreateCliente(array $data): Cliente
    {
        return Cliente::updateOrCreate(
            [
                'nombre' => $data['cliente_nombre'],
                'empresa' => $data['cliente_empresa'],
            ],
            [
                'telefono' => $data['cliente_telefono'],
            ],
        );
    }
}
