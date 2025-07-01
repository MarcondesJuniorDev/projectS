<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'corporate_name',
        'cnpj',
        'state_registration',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'notes',
    ];

    protected $casts = [
        'cnpj' => 'string',
        'state_registration' => 'string',
        'zip_code' => 'string',
        'phone' => 'string',
        'contact_person_phone' => 'string',
        'email' => 'string',
        'contact_person_email' => 'string',
    ];

    // Add a scope for active clients
    public function scopeActive($query)
    {
        return $query->whereNotNull('email');
    }

    // Add a helper method to format the full address
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state}, {$this->zip_code}";
    }

    public static function pluck($column, $key = null)
    {
        return self::all()->pluck($column, $key);
    }
}
