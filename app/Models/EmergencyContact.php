<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'relationship',
        'phone_number',
        'alternative_phone',
        'email',
        'address'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
