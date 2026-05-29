<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteMensualExport implements FromArray, ShouldAutoSize, WithHeadings, WithTitle
{
    public function __construct(private readonly array $report)
    {
    }

    public function headings(): array
    {
        return ['Concepto', 'Valor'];
    }

    public function array(): array
    {
        $rows = [
            ['Desde', $this->report['desde']],
            ['Hasta', $this->report['hasta']],
            ['Total libras vendidas', $this->report['totalLibrasVendidas']],
            ['Total ingresos', $this->report['totalIngresos']],
            ['Total gastos', $this->report['totalGastos']],
            ['Total inversiones', $this->report['totalInversiones']],
            ['Ganancia neta', $this->report['gananciaNeta']],
            ['Flujo despues de inversion', $this->report['flujoDespuesInversion']],
        ];

        $rows[] = ['', ''];
        $rows[] = ['Ventas por cliente', ''];

        foreach ($this->report['ventasPorCliente'] as $venta) {
            $rows[] = [$venta->cliente?->nombre_comercial ?? 'Sin cliente', $venta->total_cliente];
        }

        $rows[] = ['Gastos por tipo', ''];

        foreach ($this->report['gastosPorTipo'] as $gasto) {
            $rows[] = [str_replace('_', ' ', ucfirst($gasto->tipo)), $gasto->total_tipo];
        }

        $rows[] = ['', ''];
        $rows[] = ['Inversiones por tipo', ''];

        foreach ($this->report['inversionesPorTipo'] as $inversion) {
            $rows[] = [str_replace('_', ' ', ucfirst($inversion->tipo)), $inversion->total_tipo];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Reporte Wini';
    }
}
