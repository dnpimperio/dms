<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'date_of_birth',
        'gender',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function currentRoom()
    {
        // Simple relationship - we'll enhance this later with proper room assignments
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    // For backward compatibility
    public function currentAssignment()
    {
        return $this->currentRoom();
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class);
    }
}
