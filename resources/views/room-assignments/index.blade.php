@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Room Assignments</h2>
        <a href="{{ route('room-assignments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Assignment
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('room-assignments.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search room or tenant..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('room-assignments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Tenant</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Monthly Rent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->room->room_number }}</td>
                                <td>{{ $assignment->tenant->full_name }}</td>
                                <td>{{ $assignment->start_date->format('M d, Y') }}</td>
                                <td>{{ $assignment->end_date ? $assignment->end_date->format('M d, Y') : 'Ongoing' }}</td>
                                <td>₱{{ number_format($assignment->monthly_rent, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $assignment->status === 'active' ? 'success' : 
                                        ($assignment->status === 'pending' ? 'warning' : 'secondary')
                                    }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('room-assignments.show', $assignment) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('room-assignments.edit', $assignment) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('room-assignments.destroy', $assignment) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No room assignments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
