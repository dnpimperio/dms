<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'capacity',
        'price',
        'status',
        'description',
        'amenities',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'amenities' => 'array',
    ];

    // Relationships
    public function currentTenant()
    {
        // For now, let's use a simple approach - we'll enhance this later when we have proper room assignments
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function utilityReadings()
    {
        return $this->hasMany(UtilityReading::class);
    }
}
