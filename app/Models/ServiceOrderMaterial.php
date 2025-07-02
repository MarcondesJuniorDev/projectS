<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'material_id',
        'quantity_used',
        'cost_price_at_use',
    ];

    protected $casts = [
        'cost_price_at_use' => 'decimal:2',
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
