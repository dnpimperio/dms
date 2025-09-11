<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'type',
        'rate',
        'capacity',
        'current_occupants',
        'status',
        'description'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'capacity' => 'integer',
        'current_occupants' => 'integer'
    ];

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    public function isReserved()
    {
        return $this->status === 'reserved';
    }

    public function isUnderMaintenance()
    {
        return $this->status === 'maintenance';
    }

    public function hasSpace()
    {
        return $this->current_occupants < $this->capacity;
    }

    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function currentAssignments()
    {
        return $this->hasMany(RoomAssignment::class)->where('status', 'active');
    }

    public function getCurrentOccupantsAttribute()
    {
        return $this->currentAssignments()->count();
    }
}
