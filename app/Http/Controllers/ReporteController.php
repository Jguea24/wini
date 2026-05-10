<?php

namespace App\Http\Controllers;

use App\Exports\ReporteMensualExport;
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

    public function pdf(Request $request, ReporteFinancieroService $reportes)
    {
        $report = $this->reportFromRequest($request, $reportes);

        return app('dompdf.wrapper')
            ->loadView('reportes.pdf.mensual', $report)
            ->download('reporte-wini.pdf');
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
