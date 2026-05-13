<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inversiones\StoreInversionRequest;
use App\Http\Requests\Inversiones\UpdateInversionRequest;
use App\Models\Inversion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InversionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'mes' => ['nullable', 'date_format:Y-m'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $query = Inversion::query()
            ->with('user')
            ->when(isset($filters['mes']), fn ($query) => $query->whereYear('fecha', substr($filters['mes'], 0, 4))->whereMonth('fecha', substr($filters['mes'], 5, 2)))
            ->betweenDates($filters['desde'] ?? null, $filters['hasta'] ?? null);

        $totalInversiones = (float) (clone $query)->sum('monto');

        $inversiones = $query
            ->latest('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('inversiones.index', compact('inversiones', 'totalInversiones'));
    }

    public function create(): View
    {
        return view('inversiones.create');
    }

    public function store(StoreInversionRequest $request): RedirectResponse
    {
        try {
            Inversion::create($request->safe()->merge([
                'user_id' => $request->user()->id,
                'created_by' => $request->user()->id,
            ])->all());
        } catch (\Throwable $exception) {
            Log::error('No se pudo registrar la inversion.', ['exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo registrar la inversion.'])->withInput();
        }

        return redirect()->route('inversiones.index')->with('status', 'Inversion registrada correctamente.');
    }

    public function edit(Inversion $inversion): View
    {
        Gate::authorize('update', $inversion);

        return view('inversiones.edit', compact('inversion'));
    }

    public function update(UpdateInversionRequest $request, Inversion $inversion): RedirectResponse
    {
        try {
            $inversion->update($request->validated() + ['updated_by' => $request->user()->id]);
        } catch (\Throwable $exception) {
            Log::error('No se pudo actualizar la inversion.', ['inversion_id' => $inversion->id, 'exception' => $exception]);

            return back()->withErrors(['general' => 'No se pudo actualizar la inversion.'])->withInput();
        }

        return redirect()->route('inversiones.index')->with('status', 'Inversion actualizada correctamente.');
    }

    public function destroy(Inversion $inversion): RedirectResponse
    {
        Gate::authorize('delete', $inversion);

        $inversion->update(['deleted_by' => auth()->id()]);
        $inversion->delete();

        return redirect()->route('inversiones.index')->with('status', 'Inversion eliminada correctamente.');
    }
}
