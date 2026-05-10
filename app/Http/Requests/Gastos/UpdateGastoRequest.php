<?php

namespace App\Http\Requests\Gastos;

use App\Models\Gasto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGastoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $gasto = $this->route('gasto');

        return $this->user()?->can('update', $gasto) ?? false;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'tipo' => ['required', Rule::in(Gasto::TIPOS)],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'monto' => ['required', 'numeric', 'gte:0', 'max:999999999.99'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo' => strtolower(trim((string) $this->input('tipo'))),
            'descripcion' => trim((string) $this->input('descripcion')) ?: null,
        ]);
    }
}
