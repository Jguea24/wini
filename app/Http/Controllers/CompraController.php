<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompraController extends Controller
{
    public function index(Request $request): View
    {
        $compras = Compra::with('proveedor')
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('fecha', '>=', $request->desde))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('fecha', '<=', $request->hasta))
            ->latest('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('compras.index', compact('compras'));
    }

    public function create(): View
    {
        return view('compras.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'proveedor' => ['required', 'string', 'max:255'],
            'libras' => ['required', 'numeric', 'gt:0'],
            'precio_libra' => ['required', 'numeric', 'gte:0'],
        ]);

        $proveedor = Proveedor::firstOrCreate(['nombre' => trim($data['proveedor'])]);

        Compra::create([
            'proveedor_id' => $proveedor->id,
            'fecha' => $data['fecha'],
            'libras' => $data['libras'],
            'precio_libra' => $data['precio_libra'],
            'total_pagado' => round($data['libras'] * $data['precio_libra'], 2),
        ]);

        return redirect()->route('compras.index')->with('status', 'Compra registrada correctamente.');
    }
}
