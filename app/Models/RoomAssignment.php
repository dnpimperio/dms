<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAssignment extends Model
{
    protected $fillable = [
        'room_id',
        'tenant_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Check if the room is available for the given period
    public static function isRoomAvailable($roomId, $startDate, $endDate, $excludeAssignmentId = null)
    {
        $query = self::where('room_id', $roomId)
            ->where('status', '!=', 'completed')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeAssignmentId) {
            $query->where('id', '!=', $excludeAssignmentId);
        }

        return !$query->exists();
    }
}
