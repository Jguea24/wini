<?php

namespace App\Http\Controllers;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Factura;
use App\Models\Venta;
use App\Services\FacturaPdfService;
use App\Services\FacturaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FacturaController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'estado' => ['nullable', Rule::in(Factura::ESTADOS)],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $facturas = Factura::query()
            ->with(['venta.cliente', 'user', 'actualizador', 'anulador'])
            ->when($filters['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->betweenDates($filters['desde'] ?? null, $filters['hasta'] ?? null)
            ->orderBy('numero')
            ->paginate(10)
            ->withQueryString();

        $totalFacturado = (float) Factura::query()
            ->when($filters['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->betweenDates($filters['desde'] ?? null, $filters['hasta'] ?? null)
            ->sum('total');

        return view('facturas.index', compact('facturas', 'totalFacturado'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'venta_id' => ['required', 'exists:ventas,id'],
        ]);

        try {
            $factura = DB::transaction(function () use ($request, $data): Factura {
                $venta = Venta::query()
                    ->with('factura')
                    ->lockForUpdate()
                    ->findOrFail($data['venta_id']);

                if ($venta->factura) {
                    return $venta->factura;
                }

                return app(FacturaService::class)->createForVenta($venta, $request->user());
            });
        } catch (\Throwable $exception) {
            Log::error('No se pudo generar la factura.', ['exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo generar la factura.']);
        }

        return redirect()->route('facturas.show', $factura)->with('status', 'Factura generada correctamente.');
    }

    public function show(Factura $factura, FacturaPdfService $pdfService): View
    {
        return view('facturas.show', $pdfService->viewData($factura));
    }

    public function update(Request $request, Factura $factura): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(Factura::ESTADOS)],
            'observacion' => ['nullable', 'string', 'max:1000'],
        ]);

        $isAnulada = $data['estado'] === 'anulada';

        $factura->update([
            'estado' => $data['estado'],
            'observacion' => trim((string) ($data['observacion'] ?? '')) ?: null,
            'updated_by' => $request->user()->id,
            'anulada_by' => $isAnulada ? ($factura->anulada_by ?? $request->user()->id) : null,
            'anulada_at' => $isAnulada ? ($factura->anulada_at ?? now()) : null,
        ]);

        return redirect()->route('facturas.show', $factura)->with('status', 'Factura actualizada correctamente.');
    }

    public function pdf(Factura $factura, FacturaPdfService $pdfService)
    {
        return app('dompdf.wrapper')
            ->loadView('facturas.pdf.show', $pdfService->viewData($factura))
            ->download("factura-{$factura->numero}.pdf");
    }

    public function sendEmail(Factura $factura): RedirectResponse
    {
        $factura->loadMissing('venta.cliente');

        if (! $factura->venta || ! $factura->venta->cliente) {
            return back()->withErrors(['general' => 'No se puede enviar la factura porque no tiene un cliente asociado.']);
        }

        $cliente = $factura->venta->cliente;

        if (! $cliente->correo) {
            return back()->withErrors(['general' => 'No se puede enviar la factura porque el cliente no tiene un correo electrónico registrado.']);
        }

        try {
            SendInvoiceEmailJob::dispatch($factura->id);
        } catch (\Throwable $exception) {
            Log::error('Error al solicitar envío de factura por correo.', [
                'factura_id' => $factura->id,
                'exception' => $exception,
            ]);

            return back()->withErrors(['general' => 'Ocurrió un error al procesar el envío de la factura.']);
        }

        return back()->with('status', "La factura N.º {$factura->numero} ha sido enviada al correo {$cliente->correo}.");
    }
}
