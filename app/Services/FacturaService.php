<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Setting;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class FacturaService
{
    public function createForVenta(Venta $venta, User $user): Factura
    {
        $venta->loadMissing('factura');

        if ($venta->factura) {
            return $venta->factura;
        }

        $amounts = $this->amountsForVenta($venta);

        return Factura::create([
            'venta_id' => $venta->id,
            'user_id' => $user->id,
            'numero' => $this->nextNumber(),
            'fecha_emision' => now()->toDateString(),
            'subtotal' => $amounts['subtotal'],
            'descuento' => 0,
            'impuesto' => $amounts['impuesto'],
            'total' => $amounts['total'],
            'estado' => 'emitida',
        ]);
    }

    public function syncAmountsForVenta(Venta $venta, User $user): ?Factura
    {
        $venta->loadMissing('factura');

        if (! $venta->factura || $venta->factura->estado === 'anulada') {
            return $venta->factura;
        }

        $amounts = $this->amountsForVenta($venta);

        $venta->factura->update([
            'subtotal' => $amounts['subtotal'],
            'impuesto' => $amounts['impuesto'],
            'total' => $amounts['total'],
            'updated_by' => $user->id,
        ]);

        return $venta->factura->refresh();
    }

    /**
     * @return array{subtotal: float, impuesto: float, total: float}
     */
    private function amountsForVenta(Venta $venta): array
    {
        $subtotal = (float) $venta->total;
        $taxRate = (float) Setting::getValue('invoice_tax_rate', '0');
        $impuesto = round($subtotal * ($taxRate / 100), 2);

        return [
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $subtotal + $impuesto,
        ];
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
