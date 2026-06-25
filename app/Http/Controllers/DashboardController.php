<?php

namespace App\Http\Controllers;

use App\Services\CocoaMarketService;
use App\Services\ReporteFinancieroService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ReporteFinancieroService $reportes, CocoaMarketService $market): View
    {
        $data = $request->validate([
            'mes' => ['nullable', 'integer', 'between:1,12'],
            'anio' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        return view('dashboard', $reportes->dashboardFor(
            (int) ($data['anio'] ?? now()->year),
            (int) ($data['mes'] ?? now()->month),
        ) + [
            'cocoaMarket' => $market->dashboardData(),
        ]);
    }
}
