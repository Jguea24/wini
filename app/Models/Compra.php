<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['proveedor_id', 'fecha', 'libras', 'precio_libra', 'total_pagado'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'libras' => 'decimal:2',
            'precio_libra' => 'decimal:2',
            'total_pagado' => 'decimal:2',
        ];
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
