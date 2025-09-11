<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomAssignment;
use App\Models\MaintenanceRequest;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        $current_assignment = RoomAssignment::with('room')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->first();

        $maintenance_requests = MaintenanceRequest::where('tenant_id', $tenant->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.tenant', compact('current_assignment', 'maintenance_requests'));
    }
}
