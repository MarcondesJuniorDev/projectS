<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
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
        'contact_person_role',
        'contact_person_phone',
        'products_services_offered',
        'payment_terms',
        'notes',
    ];
    protected $casts = [
        'cnpj' => 'string',
        'state_registration' => 'string',
        'zip_code' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'contact_person_phone' => 'string',
    ];
}
