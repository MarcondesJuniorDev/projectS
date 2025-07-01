<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technician extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'cpf',
        'rg',
        'phone',
        'email',
        'specialities',
        'status',
        'notes',
    ];

    protected $casts = [
        'cpf' => 'string',
        'rg' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'specialities' => 'array', // Assuming specialities is stored as a JSON array
    ];

    protected $hidden = [
        'cpf',
        'rg',
        'phone',
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
