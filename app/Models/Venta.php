<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    public const METODOS_PAGO = ['efectivo', 'transferencia'];

    protected $fillable = ['cliente_id', 'user_id', 'created_by', 'updated_by', 'deleted_by', 'fecha', 'libras', 'precio_por_libra', 'total', 'metodo_pago'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'libras' => 'decimal:2',
            'precio_por_libra' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeBetweenDates($query, ?string $desde, ?string $hasta)
    {
        return $query
            ->when($desde, fn ($query) => $query->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn ($query) => $query->whereDate('fecha', '<=', $hasta));
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('fecha', $year)->whereMonth('fecha', $month);
    }
}
