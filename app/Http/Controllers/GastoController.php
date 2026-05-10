<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gastos\StoreGastoRequest;
use App\Http\Requests\Gastos\UpdateGastoRequest;
use App\Models\Gasto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GastoController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'mes' => ['nullable', 'date_format:Y-m'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $gastos = Gasto::query()
            ->with('user')
            ->when(isset($filters['mes']), fn ($query) => $query->whereYear('fecha', substr($filters['mes'], 0, 4))->whereMonth('fecha', substr($filters['mes'], 5, 2)))
            ->betweenDates($filters['desde'] ?? null, $filters['hasta'] ?? null)
            ->latest('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('gastos.index', compact('gastos'));
    }

    public function create(): View
    {
        return view('gastos.create');
    }

    public function store(StoreGastoRequest $request): RedirectResponse
    {
        try {
            Gasto::create($request->safe()->merge([
                'user_id' => $request->user()->id,
                'created_by' => $request->user()->id,
            ])->all());
        } catch (\Throwable $exception) {
            Log::error('No se pudo registrar el gasto.', ['exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo registrar el gasto.'])->withInput();
        }

        return redirect()->route('gastos.index')->with('status', 'Gasto registrado correctamente.');
    }

    public function edit(Gasto $gasto): View
    {
        Gate::authorize('update', $gasto);

        return view('gastos.edit', compact('gasto'));
    }

    public function update(UpdateGastoRequest $request, Gasto $gasto): RedirectResponse
    {
        try {
            $gasto->update($request->validated() + ['updated_by' => $request->user()->id]);
        } catch (\Throwable $exception) {
            Log::error('No se pudo actualizar el gasto.', ['gasto_id' => $gasto->id, 'exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo actualizar el gasto.'])->withInput();
        }

        return redirect()->route('gastos.index')->with('status', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto): RedirectResponse
    {
        Gate::authorize('delete', $gasto);

        $gasto->update(['deleted_by' => auth()->id()]);
        $gasto->delete();

        return redirect()->route('gastos.index')->with('status', 'Gasto eliminado correctamente.');
    }
}
