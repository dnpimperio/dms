<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'created_by',
        'bill_date',
        'room_rate',
        'electricity',
        'water',
        'other_charges',
        'other_charges_description',
        'total_amount',
        'status',
        'amount_paid',
        'due_date',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'room_rate' => 'decimal:2',
        'electricity' => 'decimal:2',
        'water' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
