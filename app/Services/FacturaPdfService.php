<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class FacturaPdfService
{
    public function __construct(private readonly PdfQrCodeService $qrCode)
    {
    }

    public function viewData(Factura $factura): array
    {
        $factura->loadMissing(['venta.cliente', 'venta.user', 'user', 'actualizador', 'anulador']);
        $company = $this->companyData();
        $invoiceMeta = $this->invoiceMeta($factura, $company);

        return [
            'factura' => $factura,
            'company' => $company,
            'invoiceMeta' => $invoiceMeta,
            'footer' => Setting::getValue('report_footer', 'Producto sostenible'),
            'signaturePath' => $this->invoiceSignaturePath(),
            'signatureName' => Setting::getValue('invoice_signature_name', 'Johnny Grefa'),
            'signatureRole' => Setting::getValue('invoice_signature_role', 'CEO de Wini'),
            'qrCodeDataUri' => $this->qrCode->dataUri($this->invoiceQrPayload($factura, $company, $invoiceMeta)),
        ];
    }

    public function render(Factura $factura): string
    {
        return app('dompdf.wrapper')
            ->loadView('facturas.pdf.show', $this->viewData($factura))
            ->output();
    }

    private function companyData(): array
    {
        return [
            'name' => Setting::getValue('company_name', 'Wini'),
            'ruc' => Setting::getValue('company_ruc', ''),
            'address' => Setting::getValue('company_address', ''),
            'phone' => Setting::getValue('company_phone', ''),
            'email' => Setting::getValue('company_email', ''),
            'branch_address' => Setting::getValue('company_branch_address', Setting::getValue('company_address', '')),
            'accounting_required' => Setting::getValue('company_accounting_required', 'NO'),
        ];
    }

    private function invoiceMeta(Factura $factura, array $company): array
    {
        return [
            'document_type' => 'FACTURA',
            'document_code' => '01',
            'authorization_number' => $this->authorizationNumber($factura),
            'access_key' => $this->accessKey($factura, $company),
            'issued_at' => ($factura->created_at ?? $factura->fecha_emision->startOfDay())->format('d/m/Y H:i:s'),
            'environment' => Setting::getValue('invoice_environment', 'PRODUCCION'),
            'emission' => Setting::getValue('invoice_emission_type', 'NORMAL'),
            'payment_form' => strtoupper(str_replace('_', ' ', $factura->venta->metodo_pago ?? 'efectivo')),
        ];
    }

    private function invoiceQrPayload(Factura $factura, array $company, array $invoiceMeta): string
    {
        $cliente = $factura->venta?->cliente;

        return implode("\n", [
            'WINI - FACTURA',
            'Empresa: '.$company['name'],
            'RUC: '.($company['ruc'] ?: 'No registrado'),
            'Numero: '.$factura->numero,
            'Autorizacion: '.$invoiceMeta['authorization_number'],
            'Clave acceso: '.$invoiceMeta['access_key'],
            'Fecha: '.$factura->fecha_emision->format('Y-m-d'),
            'Cliente: '.($cliente?->nombre_comercial ?? 'Sin cliente'),
            'Identificacion: '.($cliente?->identificacion ?: 'No registrada'),
            'Total: $'.number_format($factura->total, 2, '.', ''),
            'Estado: '.ucfirst($factura->estado),
            'URL: '.route('facturas.show', $factura),
        ]);
    }

    private function authorizationNumber(Factura $factura): string
    {
        $digits = preg_replace('/\D/', '', $factura->numero) ?: (string) $factura->id;

        return str_pad(substr($digits, -10), 10, '0', STR_PAD_LEFT);
    }

    private function accessKey(Factura $factura, array $company): string
    {
        $date = $factura->fecha_emision->format('dmY');
        $ruc = str_pad(substr(preg_replace('/\D/', '', $company['ruc']) ?: '0', 0, 13), 13, '0', STR_PAD_LEFT);
        $number = str_pad(substr(preg_replace('/\D/', '', $factura->numero) ?: (string) $factura->id, -9), 9, '0', STR_PAD_LEFT);
        $seed = $date.'01'.$ruc.'2'.$number.str_pad((string) $factura->id, 8, '0', STR_PAD_LEFT);
        $checksum = str_pad((string) (abs(crc32($seed)) % 100000000), 8, '0', STR_PAD_LEFT);

        return substr($seed.$checksum, 0, 49);
    }

    private function invoiceSignaturePath(): ?string
    {
        $path = Setting::getValue('invoice_signature_path');

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->path($path);
    }
}
