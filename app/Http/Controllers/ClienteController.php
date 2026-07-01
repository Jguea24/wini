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
        $data = $request->validate($this->rules());

        Cliente::create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('clientes.index')->with('status', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $request->validate($this->rules());

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

    private function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'identificacion' => [
                'nullable',
                'string',
                'max:13',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $this->isValidEcuadorIdentification((string) $value)) {
                        $fail('Ingrese una cedula o RUC ecuatoriano valido.');
                    }
                },
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
        ];
    }

    private function isValidEcuadorIdentification(string $value): bool
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if ($digits === '') {
            return true;
        }

        if (strlen($digits) === 10) {
            return $this->isValidEcuadorCedula($digits);
        }

        if (strlen($digits) !== 13 || substr($digits, 10, 3) === '000') {
            return false;
        }

        $thirdDigit = (int) $digits[2];

        if ($thirdDigit < 6) {
            return $this->isValidEcuadorCedula(substr($digits, 0, 10));
        }

        if ($thirdDigit === 6) {
            return $this->isValidEcuadorRuc($digits, [3, 2, 7, 6, 5, 4, 3, 2], 8);
        }

        if ($thirdDigit === 9) {
            return $this->isValidEcuadorRuc($digits, [4, 3, 2, 7, 6, 5, 4, 3, 2], 9);
        }

        return false;
    }

    private function isValidEcuadorCedula(string $cedula): bool
    {
        if (! preg_match('/^\d{10}$/', $cedula)) {
            return false;
        }

        $province = (int) substr($cedula, 0, 2);

        if ($province < 1 || $province > 24) {
            return false;
        }

        $digits = array_map('intval', str_split($cedula));
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $value = $i % 2 === 0 ? $digits[$i] * 2 : $digits[$i];
            $sum += $value > 9 ? $value - 9 : $value;
        }

        $verifier = (10 - ($sum % 10)) % 10;

        return $verifier === $digits[9];
    }

    private function isValidEcuadorRuc(string $ruc, array $coefficients, int $verifierPosition): bool
    {
        $province = (int) substr($ruc, 0, 2);

        if ($province < 1 || $province > 24) {
            return false;
        }

        $digits = array_map('intval', str_split($ruc));
        $sum = 0;

        foreach ($coefficients as $index => $coefficient) {
            $sum += $digits[$index] * $coefficient;
        }

        $verifier = 11 - ($sum % 11);
        $verifier = $verifier === 11 ? 0 : $verifier;
        $verifier = $verifier === 10 ? 1 : $verifier;

        return $verifier === $digits[$verifierPosition];
    }
}
