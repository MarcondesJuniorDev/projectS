<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'os_number',
        'client_id',
        'service_location_id',
        'requester_name',
        'requester_phone',
        'requester_email',
        'problem_description',
        'priority',
        'status',
        'opened_at',
        'scheduled_at',
        'started_at',
        'completed_at',
        'assigned_technician_id',
        'service_performed_description',
        'time_spent_hours',
        'customer_signature_path',
        'customer_feedback',
        'customer_rating',
        'internal_notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent_hours' => 'decimal:2',
        'customer_rating' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function serviceLocation(): BelongsTo
    {
        return $this->belongsTo(ServiceLocation::class, 'service_location_id', 'id');
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'assigned_technician_id', 'id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ServiceOrderMaterial::class, 'service_order_id', 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(ServiceOrderService::class, 'service_order_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($serviceOrder) {
            if (empty($serviceOrder->os_number)) {
                $prefix = 'OS' . now()->format('Ymd');
                $latestOs = static::where('os_number', 'like', $prefix . '%')
                    ->latest('id')
                    ->first();
                $lastNumber = $latestOs ? intval(substr($latestOs->os_number, -4)) : 0;
                $newNumber = $lastNumber + 1;
                $serviceOrder->os_number = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }

}
