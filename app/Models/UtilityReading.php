<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'utility_type_id',
        'reading_value',
        'previous_reading',
        'consumption',
        'reading_date',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'reading_value' => 'decimal:2',
        'previous_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'reading_date' => 'date',
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
