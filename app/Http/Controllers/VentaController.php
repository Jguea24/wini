<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\StoreVentaRequest;
use App\Http\Requests\Ventas\UpdateVentaRequest;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $ventas = Venta::with(['cliente', 'user', 'factura'])
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

    public function store(StoreVentaRequest $request, VentaService $ventas): RedirectResponse
    {
        try {
            $resultado = $ventas->registrar($request->validated(), $request->user());

            $status = 'Venta registrada correctamente.';

            if ($resultado['factura_email_solicitado']) {
                $status .= ' La factura será enviada al correo del cliente.';
            } else {
                $status .= ' No fue posible enviar la factura por correo porque el cliente no tiene correo registrado.';
            }

            return redirect()->route('ventas.index')->with('status', $status);
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

    public function update(UpdateVentaRequest $request, Venta $venta, VentaService $ventas): RedirectResponse
    {
        try {
            $ventas->actualizar($venta, $request->validated(), $request->user());

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
}
