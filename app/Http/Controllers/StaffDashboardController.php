<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tasks = MaintenanceRequest::query()
            ->when($user->assigned_area, function ($query) use ($user) {
                return $query->where('area', $user->assigned_area);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completed_today' => MaintenanceRequest::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('dashboard.staff', compact('tasks', 'stats'));
    }
}
