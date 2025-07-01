<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'unit_of_measurement',
        'cost_price',
        'min_stock_level',
        'max_stock_level',
        'current_stock',
        'description',
        'location_in_warehouse',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'current_stock' => 'integer',
    ];
}
