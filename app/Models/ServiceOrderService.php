<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceOrderService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'service_template_id',
        'description',
        'price',
        'time_spent_hours',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'time_spent_hours' => 'decimal:2',
    ];

    public function serviceOder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id', 'id');
    }

    public function serviceTemplate(): BelongsTo
    {
        return $this->belongsTo(ServiceTemplate::class, 'service_template_id', 'id');
    }
}
