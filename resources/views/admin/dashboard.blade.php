@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col">
            <h2>Admin Dashboard</h2>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Room
                </a>
                <a href="{{ route('admin.tenants.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> New Tenant
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Stats -->
        <div class="col-md-3 mb-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Rooms</h5>
                    <h2 class="mb-0">{{ App\Models\Room::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Active Tenants</h5>
                    <h2 class="mb-0">{{ App\Models\Tenant::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">Occupied Rooms</h5>
                    <h2 class="mb-0">{{ App\Models\Room::where('status', 'occupied')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">Pending Requests</h5>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activities -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Add recent activities here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.rooms.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-door-open"></i> Room Management
                        </a>
                        <a href="{{ route('admin.tenants.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users"></i> Tenant Management
                        </a>
                        <a href="{{ route('admin.room-assignments.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-key"></i> Room Assignments
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-tools"></i> Maintenance Requests
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-invoice-dollar"></i> Billing & Payments
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog"></i> System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
