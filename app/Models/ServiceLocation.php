<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'reference_point',
        'notes',
    ];

    public function client(): HasMany
    {
        return $this->hasMany(Client::class, 'id', 'client_id');
    }
}
