<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the utility rates for this utility type.
     */
    public function rates()
    {
        return $this->hasMany(UtilityRate::class);
    }

    /**
     * Get the utility readings for this utility type.
     */
    public function readings()
    {
        return $this->hasMany(UtilityReading::class);
    }

    /**
     * Get the current active rate for this utility type.
     */
    public function currentRate()
    {
        return $this->rates()
            ->where('effective_from', '<=', now())
            ->where(function ($query) {
                $query->whereNull('effective_until')
                      ->orWhere('effective_until', '>=', now());
            })
            ->orderBy('effective_from', 'desc')
            ->first();
    }

    /**
     * Scope a query to only include active utility types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the formatted unit display.
     */
    public function getFormattedUnitAttribute()
    {
        return $this->unit ?: 'units';
    }
}
