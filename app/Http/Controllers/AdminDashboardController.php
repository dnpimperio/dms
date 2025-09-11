<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomAssignment;
use App\Models\Tenant;
use App\Models\MaintenanceRequest;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'total_tenants' => Tenant::count(),
            'active_leases' => RoomAssignment::where('status', 'active')->count(),
            'pending_maintenance' => MaintenanceRequest::where('status', 'pending')->count(),
        ];

        $recent_assignments = RoomAssignment::with(['room', 'tenant'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_assignments'));
    }
}
