<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'nationality',
        'occupation',
        'university',
        'course',
        'provincial_address',
        'phone_number',
        'alternative_phone',
        'personal_email',
        'current_address',
        'id_type',
        'id_number',
        'id_image_path',
        'remarks',
    ];

    protected $casts = [
        'birth_date' => 'date',
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

    // Accessors
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
