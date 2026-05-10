<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use HasFactory, SoftDeletes;

    public const TIPOS = ['transporte', 'mantenimiento', 'mano_obra', 'insumos', 'servicios', 'otros'];

    protected $fillable = ['user_id', 'created_by', 'updated_by', 'deleted_by', 'fecha', 'tipo', 'descripcion', 'monto'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
