@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Staff Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending Tasks</h5>
                    <h2 class="card-text">{{ $stats['pending_tasks'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <h2 class="card-text">{{ $stats['in_progress'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed Today</h5>
                    <h2 class="card-text">{{ $stats['completed_today'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Tasks -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Maintenance Tasks</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary">All</button>
                    <button type="button" class="btn btn-sm btn-outline-warning">Pending</button>
                    <button type="button" class="btn btn-sm btn-outline-info">In Progress</button>
                    <button type="button" class="btn btn-sm btn-outline-success">Completed</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Issue</th>
                                <th>Reported</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->room->room_number }}</td>
                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                    <td>{{ $task->created_at->diffForHumans() }}</td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" 
                                                data-task-id="{{ $task->id }}">
                                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>
                                                In Progress
                                            </option>
                                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                data-bs-target="#taskModal{{ $task->id }}">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $tasks->links() }}
                </div>
            @else
                <p class="text-center">No maintenance tasks found.</p>
            @endif
        </div>
    </div>
</div>

@foreach($tasks as $task)
    <!-- Task Detail Modal -->
    <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-4">Room</dt>
                        <dd class="col-sm-8">{{ $task->room->room_number }}</dd>

                        <dt class="col-sm-4">Reported By</dt>
                        <dd class="col-sm-8">{{ $task->tenant->full_name }}</dd>

                        <dt class="col-sm-4">Reported On</dt>
                        <dd class="col-sm-8">{{ $task->created_at->format('M d, Y h:i A') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">{{ ucfirst($task->status) }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $task->description }}</dd>

                        @if($task->photos)
                            <dt class="col-sm-4">Photos</dt>
                            <dd class="col-sm-8">
                                <div class="row">
                                    @foreach($task->photos as $photo)
                                        <div class="col-6 mb-2">
                                            <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                </div>
                            </dd>
                        @endif
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('scripts')
<script>
    // Status update handling
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const status = this.value;
            
            // Add AJAX call here to update status
            fetch(`/maintenance-tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Status updated successfully');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        });
    });
</script>
@endpush
