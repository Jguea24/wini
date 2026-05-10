<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'empresa', 'telefono', 'created_by', 'updated_by'];

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getNombreComercialAttribute(): string
    {
        return trim($this->nombre.' '.($this->empresa ? '('.$this->empresa.')' : ''));
    }
}
