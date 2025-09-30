<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'utility_type_id',
        'room_id',
        'reading_date',
        'current_reading',
        'previous_reading',
        'consumption',
        'notes',
        'recorded_by',
        'bill_id',
    ];

    protected $casts = [
        'reading_date' => 'date',
        'current_reading' => 'decimal:2',
        'previous_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the utility type for this reading.
     */
    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }

    /**
     * Get the room for this reading.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who recorded this reading.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the bill associated with this reading.
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Get all bills that include this reading (removed as we use direct bill_id relationship).
     */
    // public function bills()
    // {
    //     return $this->belongsToMany(Bill::class, 'bill_utility_readings');
    // }

    /**
     * Scope a query to only include readings for a specific utility type.
     */
    public function scopeForUtilityType($query, $utilityTypeId)
    {
        return $query->where('utility_type_id', $utilityTypeId);
    }

    /**
     * Scope a query to only include readings for a specific room.
     */
    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /**
     * Scope a query to only include readings within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('reading_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include unbilled readings.
     */
    public function scopeUnbilled($query)
    {
        return $query->whereNull('bill_id');
    }

    /**
     * Get the formatted consumption with unit.
     */
    public function getFormattedConsumptionAttribute()
    {
        return number_format($this->consumption, 2) . ' ' . $this->utilityType->unit;
    }

    /**
     * Calculate the cost for this reading based on the applicable rate.
     */
    public function calculateCost()
    {
        $rate = UtilityRate::where('utility_type_id', $this->utility_type_id)
            ->effectiveOn($this->reading_date)
            ->first();

        if (!$rate) {
            return 0;
        }

        return $this->consumption * $rate->rate_per_unit;
    }
}
