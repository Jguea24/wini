<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CocoaMarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'symbol',
        'price',
        'change_value',
        'change_percent',
        'currency',
        'unit',
        'quoted_at',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'change_value' => 'decimal:2',
            'change_percent' => 'decimal:4',
            'quoted_at' => 'datetime',
            'raw_payload' => 'array',
        ];
    }
}
