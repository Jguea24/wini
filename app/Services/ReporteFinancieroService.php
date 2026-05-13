<?php

namespace App\Services;

use App\Models\Gasto;
use App\Models\Inversion;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReporteFinancieroService
{
    public function monthly(?int $year = null, ?int $month = null): array
    {
        $date = Carbon::create($year ?? now()->year, $month ?? now()->month, 1);

        return $this->between($date->copy()->startOfMonth()->toDateString(), $date->copy()->endOfMonth()->toDateString());
    }

    public function between(string $desde, string $hasta): array
    {
        $ventas = Venta::betweenDates($desde, $hasta);
        $gastos = Gasto::betweenDates($desde, $hasta);
        $inversiones = Inversion::betweenDates($desde, $hasta);

        $totalLibrasVendidas = (float) $ventas->sum('libras');
        $totalIngresos = (float) $ventas->sum('total');
        $totalGastos = (float) $gastos->sum('monto');
        $totalInversiones = (float) $inversiones->sum('monto');
        $gananciaNeta = $totalIngresos - $totalGastos;

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'totalLibrasVendidas' => $totalLibrasVendidas,
            'totalIngresos' => $totalIngresos,
            'totalGastos' => $totalGastos,
            'totalInversiones' => $totalInversiones,
            'gananciaNeta' => $gananciaNeta,
            'flujoDespuesInversion' => $gananciaNeta - $totalInversiones,
            'ventasPorCliente' => Venta::with('cliente')
                ->betweenDates($desde, $hasta)
                ->selectRaw('cliente_id, SUM(libras) as libras_cliente, SUM(total) as total_cliente')
                ->groupBy('cliente_id')
                ->orderByDesc('total_cliente')
                ->limit(10)
                ->get(),
            'gastosPorTipo' => Gasto::betweenDates($desde, $hasta)
                ->selectRaw('tipo, SUM(monto) as total_tipo')
                ->groupBy('tipo')
                ->orderByDesc('total_tipo')
                ->get(),
            'inversionesPorTipo' => Inversion::betweenDates($desde, $hasta)
                ->selectRaw('tipo, SUM(monto) as total_tipo')
                ->groupBy('tipo')
                ->orderByDesc('total_tipo')
                ->get(),
        ];
    }

    public function dashboard(): array
    {
        return $this->dashboardFor(now()->year, now()->month);
    }

    public function dashboardFor(int $year, int $month): array
    {
        $actual = $this->monthly($year, $month);
        $totalIngresosGeneral = (float) Venta::sum('total');
        $totalGastosGeneral = (float) Gasto::sum('monto');
        $totalInversionesGeneral = (float) Inversion::sum('monto');
        $ventasDelMes = Venta::with('cliente')->forMonth($year, $month);
        $gastosDelMes = Gasto::forMonth($year, $month);
        $inversionesDelMes = Inversion::forMonth($year, $month);

        return array_merge($actual, [
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'totalIngresosGeneral' => $totalIngresosGeneral,
            'totalGastosGeneral' => $totalGastosGeneral,
            'totalInversionesGeneral' => $totalInversionesGeneral,
            'gananciaGeneral' => $totalIngresosGeneral - $totalGastosGeneral,
            'flujoGeneralDespuesInversion' => ($totalIngresosGeneral - $totalGastosGeneral) - $totalInversionesGeneral,
            'totalLibrasVendidasGeneral' => (float) Venta::sum('libras'),
            'precioPromedioLibra' => (float) $ventasDelMes->avg('precio_por_libra'),
            'mayorGasto' => (float) (clone $gastosDelMes)->max('monto'),
            'mayorInversion' => (float) (clone $inversionesDelMes)->max('monto'),
            'clientePrincipal' => Venta::with('cliente')
                ->forMonth($year, $month)
                ->selectRaw('cliente_id, SUM(total) as total_cliente')
                ->groupBy('cliente_id')
                ->orderByDesc('total_cliente')
                ->first(),
            'ventasVsGastos' => $this->ventasVsGastosPorMes(),
            'tendenciaMensual' => $this->tendenciaMensual(),
        ]);
    }

    private function ventasVsGastosPorMes(): Collection
    {
        return collect(range(1, 12))->map(function (int $month) {
            return [
                'mes' => $month,
                'ingresos' => (float) Venta::whereYear('fecha', now()->year)->whereMonth('fecha', $month)->sum('total'),
                'gastos' => (float) Gasto::whereYear('fecha', now()->year)->whereMonth('fecha', $month)->sum('monto'),
                'inversiones' => (float) Inversion::whereYear('fecha', now()->year)->whereMonth('fecha', $month)->sum('monto'),
            ];
        });
    }

    private function tendenciaMensual(): Collection
    {
        return $this->ventasVsGastosPorMes()->map(fn (array $row) => [
            'mes' => $row['mes'],
            'ganancia' => $row['ingresos'] - $row['gastos'],
        ]);
    }
}
