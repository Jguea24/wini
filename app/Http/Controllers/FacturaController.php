<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Setting;
use App\Models\Venta;
use App\Services\PdfQrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

                $subtotal = (float) $venta->total;
                $taxRate = (float) Setting::getValue('invoice_tax_rate', '0');
                $impuesto = round($subtotal * ($taxRate / 100), 2);

                return Factura::create([
                    'venta_id' => $venta->id,
                    'user_id' => $request->user()->id,
                    'numero' => $this->nextNumber(),
                    'fecha_emision' => now()->toDateString(),
                    'subtotal' => $subtotal,
                    'descuento' => 0,
                    'impuesto' => $impuesto,
                    'total' => $subtotal + $impuesto,
                    'estado' => 'emitida',
                ]);
            });
        } catch (\Throwable $exception) {
            Log::error('No se pudo generar la factura.', ['exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo generar la factura.']);
        }

        return redirect()->route('facturas.show', $factura)->with('status', 'Factura generada correctamente.');
    }

    public function show(Factura $factura, PdfQrCodeService $qrCode): View
    {
        $factura->load(['venta.cliente', 'venta.user', 'user', 'actualizador', 'anulador']);

        $company = $this->companyData();
        $invoiceMeta = $this->invoiceMeta($factura, $company);

        return view('facturas.show', [
            'factura' => $factura,
            'company' => $company,
            'invoiceMeta' => $invoiceMeta,
            'qrCodeDataUri' => $qrCode->dataUri($this->invoiceQrPayload($factura, $company, $invoiceMeta)),
        ]);
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

    public function pdf(Factura $factura, PdfQrCodeService $qrCode)
    {
        $factura->load(['venta.cliente', 'venta.user', 'user', 'actualizador', 'anulador']);
        $company = $this->companyData();
        $invoiceMeta = $this->invoiceMeta($factura, $company);

        return app('dompdf.wrapper')
            ->loadView('facturas.pdf.show', [
                'factura' => $factura,
                'company' => $company,
                'invoiceMeta' => $invoiceMeta,
                'footer' => Setting::getValue('report_footer', 'Producto sostenible'),
                'signaturePath' => $this->invoiceSignaturePath(),
                'signatureName' => Setting::getValue('invoice_signature_name', 'Johnny Grefa'),
                'signatureRole' => Setting::getValue('invoice_signature_role', 'CEO de Wini'),
                'qrCodeDataUri' => $qrCode->dataUri($this->invoiceQrPayload($factura, $company, $invoiceMeta)),
            ])
            ->download("factura-{$factura->numero}.pdf");
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
        return implode("\n", [
            'WINI - FACTURA',
            'Empresa: '.$company['name'],
            'RUC: '.($company['ruc'] ?: 'No registrado'),
            'Numero: '.$factura->numero,
            'Autorizacion: '.$invoiceMeta['authorization_number'],
            'Clave acceso: '.$invoiceMeta['access_key'],
            'Fecha: '.$factura->fecha_emision->format('Y-m-d'),
            'Cliente: '.$factura->venta->cliente->nombre_comercial,
            'Identificacion: '.($factura->venta->cliente->identificacion ?: 'No registrada'),
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

    private function nextNumber(): string
    {
        $year = now()->year;
        $prefix = strtoupper(preg_replace('/[^A-Z0-9-]/', '', Setting::getValue('invoice_prefix', 'FAC')) ?: 'FAC');
        $setting = DB::table('settings')->where('key', 'invoice_next_number')->lockForUpdate()->first();

        if (! $setting) {
            DB::table('settings')->insert(['key' => 'invoice_next_number', 'value' => '1']);
            $sequence = 1;
        } else {
            $sequence = max(1, (int) $setting->value);
        }

        $last = Factura::query()
            ->where('numero', 'like', "{$prefix}-{$year}-%")
            ->lockForUpdate()
            ->latest('id')
            ->first();

        if ($last) {
            $sequence = max($sequence, ((int) substr($last->numero, -6)) + 1);
        }

        DB::table('settings')->updateOrInsert(
            ['key' => 'invoice_next_number'],
            ['value' => (string) ($sequence + 1)]
        );

        return sprintf('%s-%s-%06d', $prefix, $year, $sequence);
    }
}
