<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityRate extends Model
{  
    use HasFactory;

    protected $fillable = [
        'utility_type_id',
        'rate_per_unit',
        'effective_from',
        'effective_until',
        'status',
        'created_by',
    ];

    protected $casts = [
        'rate_per_unit' => 'decimal:4',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    // Relationships
    public function utilityType()
    {
        return $this->belongsTo(UtilityType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
