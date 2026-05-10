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
            ['Ganancia neta', $this->report['gananciaNeta']],
        ];

        $rows[] = ['', ''];
        $rows[] = ['Ventas por cliente', ''];

        foreach ($this->report['ventasPorCliente'] as $venta) {
            $rows[] = [$venta->cliente?->nombre_comercial ?? 'Sin cliente', $venta->total_cliente];
        }

        $rows[] = ['', ''];
        $rows[] = ['Gastos por tipo', ''];

        foreach ($this->report['gastosPorTipo'] as $gasto) {
            $rows[] = [str_replace('_', ' ', ucfirst($gasto->tipo)), $gasto->total_tipo];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Reporte Wini';
    }
}
