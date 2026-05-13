<?php

namespace App\Http\Requests\Inversiones;

use App\Models\Inversion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInversionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'tipo' => ['required', Rule::in(Inversion::TIPOS)],
            'concepto' => ['required', 'string', 'max:160'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'monto' => ['required', 'numeric', 'gte:0', 'max:999999999.99'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo' => strtolower(trim((string) $this->input('tipo'))),
            'concepto' => trim((string) $this->input('concepto')),
            'descripcion' => trim((string) $this->input('descripcion')) ?: null,
        ]);
    }
}
