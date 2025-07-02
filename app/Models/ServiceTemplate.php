<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'standard_price',
        'estimated_time_hours',
        'requires_materials',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'standard_price' => 'decimal:2',
        'estimated_time_hours' => 'decimal:2',
        'requires_materials' => 'boolean',
    ];
}
