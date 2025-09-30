<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UtilityRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'utility_type_id',
        'rate_per_unit',
        'effective_from',
        'effective_until',
        'created_by',
    ];

    protected $casts = [
        'rate_per_unit' => 'decimal:4',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the utility type that owns this rate.
     */
    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }

    /**
     * Get the user who created this rate.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the utility readings that use this rate.
     */
    public function readings()
    {
        return $this->hasMany(UtilityReading::class, 'utility_rate_id');
    }

    /**
     * Scope a query to only include rates that are effective for a given date.
     */
    public function scopeEffectiveOn($query, $date = null)
    {
        $date = $date ?: now();
        
        return $query->where('effective_from', '<=', $date)
                    ->where(function ($query) use ($date) {
                        $query->whereNull('effective_until')
                              ->orWhere('effective_until', '>=', $date);
                    });
    }

    /**
     * Scope a query to only include current rates.
     */
    public function scopeCurrent($query)
    {
        return $query->effectiveOn();
    }

    /**
     * Check if this rate is currently active.
     */
    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->effective_from <= $now && 
               (!$this->effective_until || $this->effective_until >= $now);
    }

    /**
     * Get the formatted rate per unit.
     */
    public function getFormattedRateAttribute()
    {
        return '$' . number_format($this->rate_per_unit, 4);
    }
}
