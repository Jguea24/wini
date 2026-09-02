<?php

namespace App\Jobs;

use App\Mail\InvoicePdfMail;
use App\Models\Factura;
use App\Services\FacturaPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $facturaId)
    {
    }

    public function handle(FacturaPdfService $pdfService): void
    {
        try {
            $factura = Factura::query()
                ->with(['venta.cliente'])
                ->findOrFail($this->facturaId);

            if (! $factura->venta || ! $factura->venta->cliente) {
                Log::warning('No se envio factura por correo porque la factura no tiene venta o cliente asociado.', [
                    'factura_id' => $factura->id,
                    'venta_id' => $factura->venta_id,
                ]);

                return;
            }

            $cliente = $factura->venta->cliente;

            if (! $cliente->correo) {
                Log::info('No se envio factura por correo porque el cliente no tiene correo registrado.', [
                    'factura_id' => $factura->id,
                    'venta_id' => $factura->venta_id,
                    'cliente_id' => $cliente->id,
                ]);

                return;
            }

            $viewData = $pdfService->viewData($factura);
            $pdfContent = app('dompdf.wrapper')
                ->loadView('facturas.pdf.show', $viewData)
                ->output();

            Mail::to($cliente->correo)->send(new InvoicePdfMail(
                factura: $factura,
                nombreCliente: $cliente->nombre_comercial,
                nombreEmpresa: $viewData['company']['name'] ?: 'Wini',
                pdfContent: $pdfContent,
            ));

            Log::info('Factura enviada por correo.', [
                'factura_id' => $factura->id,
                'venta_id' => $factura->venta_id,
                'cliente_id' => $cliente->id,
                'correo' => $cliente->correo,
            ]);
        } catch (\Throwable $exception) {
            Log::error('No se pudo enviar la factura por correo.', [
                'factura_id' => $this->facturaId,
                'exception' => $exception,
            ]);
        }
    }
}
