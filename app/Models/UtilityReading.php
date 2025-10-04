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
        'current_reading',
        'previous_reading',
        'consumption',
        'reading_date',
        'recorded_by',
        'notes',
        'bill_id',
    ];

    protected $casts = [
        'current_reading' => 'decimal:2',
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

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Calculate the cost of this utility reading based on consumption and current rate
     */
    public function calculateCost()
    {
        // Get the current rate for this utility type
        $rate = UtilityRate::where('utility_type_id', $this->utility_type_id)
            ->where('status', 'active')
            ->where('effective_from', '<=', $this->reading_date)
            ->where(function ($query) {
                $query->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', $this->reading_date);
            })
            ->first();

        if (!$rate) {
            return 0; // No rate found, return 0
        }

        return $this->consumption * $rate->rate_per_unit;
    }
}
