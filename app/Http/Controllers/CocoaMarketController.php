<?php

namespace App\Http\Controllers;

use App\Services\CocoaMarketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CocoaMarketController extends Controller
{
    public function index(CocoaMarketService $market): View
    {
        return view('mercado-cacao.index', [
            'cocoaMarket' => $market->dashboardData(),
        ]);
    }

    public function refresh(Request $request, CocoaMarketService $market): RedirectResponse
    {
        $market->refresh(force: true);

        return back()->with('status', 'Precio internacional del cacao actualizado correctamente.');
    }

    public function live(CocoaMarketService $market): JsonResponse
    {
        $market->refresh(force: true);
        $data = $market->dashboardData();
        $latest = $data['latest'];

        return response()->json([
            'latest' => $latest ? [
                'price' => (float) $latest->price,
                'currency' => $latest->currency,
                'unit' => $latest->unit,
                'quoted_at' => $latest->quoted_at?->format('Y-m-d H:i'),
                'provider' => $latest->provider,
            ] : null,
            'dailyVariation' => $data['dailyVariation'],
            'weeklyVariation' => $data['weeklyVariation'],
            'history' => $data['history'],
        ]);
    }
}
