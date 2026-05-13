<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    public const ESTADOS = ['emitida', 'pagada', 'anulada'];

    protected $fillable = [
        'venta_id',
        'user_id',
        'updated_by',
        'anulada_by',
        'anulada_at',
        'numero',
        'fecha_emision',
        'subtotal',
        'descuento',
        'impuesto',
        'total',
        'estado',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'anulada_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'descuento' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function anulador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anulada_by');
    }

    public function scopeBetweenDates($query, ?string $desde, ?string $hasta)
    {
        return $query
            ->when($desde, fn ($query) => $query->whereDate('fecha_emision', '>=', $desde))
            ->when($hasta, fn ($query) => $query->whereDate('fecha_emision', '<=', $hasta));
    }
}
