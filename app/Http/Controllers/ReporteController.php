<?php

namespace App\Http\Controllers;

use App\Exports\ReporteMensualExport;
use App\Services\PdfQrCodeService;
use App\Services\ReporteFinancieroService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReporteController extends Controller
{
    public function index(Request $request, ReporteFinancieroService $reportes): View
    {
        return view('reportes.index', $this->reportFromRequest($request, $reportes));
    }

    public function excel(Request $request, ReporteFinancieroService $reportes): BinaryFileResponse
    {
        $report = $this->reportFromRequest($request, $reportes);

        return Excel::download(new ReporteMensualExport($report), 'reporte-wini.xlsx');
    }

    public function pdf(Request $request, ReporteFinancieroService $reportes, PdfQrCodeService $qrCode)
    {
        $report = $this->reportFromRequest($request, $reportes);

        return app('dompdf.wrapper')
            ->loadView('reportes.pdf.mensual', $report + [
                'qrCodeDataUri' => $qrCode->dataUri($this->reportQrPayload($report)),
            ])
            ->download('reporte-wini.pdf');
    }

    private function reportQrPayload(array $report): string
    {
        return implode("\n", [
            'WINI - REPORTE FINANCIERO',
            'Periodo: '.$report['desde'].' a '.$report['hasta'],
            'Libras vendidas: '.number_format($report['totalLibrasVendidas'], 2, '.', ''),
            'Ingresos: $'.number_format($report['totalIngresos'], 2, '.', ''),
            'Gastos: $'.number_format($report['totalGastos'], 2, '.', ''),
            'Inversiones: $'.number_format($report['totalInversiones'], 2, '.', ''),
            'Ganancia neta: $'.number_format($report['gananciaNeta'], 2, '.', ''),
            'Flujo despues de inversion: $'.number_format($report['flujoDespuesInversion'], 2, '.', ''),
            'Generado: '.now()->format('Y-m-d H:i'),
        ]);
    }

    private function reportFromRequest(Request $request, ReporteFinancieroService $reportes): array
    {
        $data = $request->validate([
            'tipo_filtro' => ['nullable', Rule::in(['mes', 'rango'])],
            'mes' => ['nullable', 'integer', 'between:1,12'],
            'anio' => ['nullable', 'integer', 'between:2000,2100'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        if (($data['tipo_filtro'] ?? 'mes') === 'rango' && isset($data['desde'], $data['hasta'])) {
            return $reportes->between($data['desde'], $data['hasta']) + ['tipoFiltro' => 'rango'];
        }

        $date = Carbon::create((int) ($data['anio'] ?? now()->year), (int) ($data['mes'] ?? now()->month), 1);

        return $reportes->monthly($date->year, $date->month) + [
            'tipoFiltro' => 'mes',
            'mes' => $date->month,
            'anio' => $date->year,
        ];
    }
}
