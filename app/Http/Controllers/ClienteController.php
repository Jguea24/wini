<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::withCount('ventas')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('empresa', 'like', "%{$buscar}%")
                    ->orWhere('identificacion', 'like', "%{$buscar}%")
                    ->orWhere('correo', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create', ['cliente' => new Cliente()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'identificacion' => ['nullable', 'string', 'max:30'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
        ]);

        Cliente::create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('clientes.index')->with('status', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'identificacion' => ['nullable', 'string', 'max:30'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
        ]);

        $cliente->update($data + ['updated_by' => $request->user()->id]);

        return redirect()->route('clientes.index')->with('status', 'Cliente actualizado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        $ventas = $cliente->ventas()->with('user')->latest('fecha')->paginate(10);

        return view('clientes.show', compact('cliente', 'ventas'));
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        abort_if($cliente->ventas()->exists(), 422, 'No se puede eliminar un cliente con ventas registradas.');

        $cliente->delete();

        return redirect()->route('clientes.index')->with('status', 'Cliente eliminado correctamente.');
    }
}
