@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Rooms</h5>
                    <p class="card-text">Total: {{ $stats['total_rooms'] }}</p>
                    <p class="card-text">Occupied: {{ $stats['occupied_rooms'] }}</p>
                    <p class="card-text">Available: {{ $stats['total_rooms'] - $stats['occupied_rooms'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Tenants & Leases</h5>
                    <p class="card-text">Total Tenants: {{ $stats['total_tenants'] }}</p>
                    <p class="card-text">Active Leases: {{ $stats['active_leases'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Maintenance</h5>
                    <p class="card-text">Pending Requests: {{ $stats['pending_maintenance'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Quick Links</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-block w-100">
                                <i class="fas fa-plus"></i> Add New Room
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('room-assignments.create') }}" class="btn btn-success btn-block w-100">
                                <i class="fas fa-key"></i> New Room Assignment
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('tenants.create') }}" class="btn btn-info btn-block w-100">
                                <i class="fas fa-user-plus"></i> Register Tenant
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="#" class="btn btn-warning btn-block w-100">
                                <i class="fas fa-tools"></i> View Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Recent Room Assignments</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Tenant</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->room->room_number }}</td>
                                        <td>{{ $assignment->tenant->full_name }}</td>
                                        <td>{{ $assignment->start_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('room-assignments.show', $assignment) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent assignments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
