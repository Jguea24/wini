<?php

namespace App\Http\Requests\Ventas;

use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'cliente_nombre' => ['required', 'string', 'max:255'],
            'cliente_empresa' => ['nullable', 'string', 'max:255'],
            'cliente_telefono' => ['nullable', 'string', 'max:30'],
            'libras' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'precio_por_libra' => ['required', 'numeric', 'gte:0', 'max:999999.99'],
            'metodo_pago' => ['required', Rule::in(Venta::METODOS_PAGO)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cliente_nombre' => trim((string) $this->input('cliente_nombre')),
            'cliente_empresa' => trim((string) $this->input('cliente_empresa')) ?: null,
            'cliente_telefono' => trim((string) $this->input('cliente_telefono')) ?: null,
            'metodo_pago' => strtolower(trim((string) $this->input('metodo_pago'))),
        ]);
    }
}
