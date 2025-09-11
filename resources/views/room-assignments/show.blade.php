@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Room Assignment Details</h4>
                        <div>
                            <a href="{{ route('room-assignments.edit', $roomAssignment) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('room-assignments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Room Information</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Room Number</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->room->room_number }}</dd>
                                
                                <dt class="col-sm-4">Floor</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->room->floor }}</dd>
                                
                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->room->type }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <h5>Tenant Information</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->tenant->full_name }}</dd>
                                
                                <dt class="col-sm-4">Phone</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->tenant->phone }}</dd>
                                
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8">{{ $roomAssignment->tenant->email }}</dd>
                            </dl>
                        </div>
                    </div>

                    <h5>Assignment Details</h5>
                    <dl class="row">
                        <dt class="col-sm-3">Start Date</dt>
                        <dd class="col-sm-9">{{ $roomAssignment->start_date->format('F d, Y') }}</dd>
                        
                        <dt class="col-sm-3">End Date</dt>
                        <dd class="col-sm-9">
                            {{ $roomAssignment->end_date ? $roomAssignment->end_date->format('F d, Y') : 'Ongoing' }}
                        </dd>
                        
                        <dt class="col-sm-3">Monthly Rent</dt>
                        <dd class="col-sm-9">₱{{ number_format($roomAssignment->monthly_rent, 2) }}</dd>
                        
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $roomAssignment->status === 'active' ? 'success' : ($roomAssignment->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($roomAssignment->status) }}
                            </span>
                        </dd>
                        
                        @if($roomAssignment->notes)
                            <dt class="col-sm-3">Notes</dt>
                            <dd class="col-sm-9">{{ $roomAssignment->notes }}</dd>
                        @endif
                        
                        <dt class="col-sm-3">Created</dt>
                        <dd class="col-sm-9">{{ $roomAssignment->created_at->format('F d, Y h:i A') }}</dd>
                        
                        <dt class="col-sm-3">Last Updated</dt>
                        <dd class="col-sm-9">{{ $roomAssignment->updated_at->format('F d, Y h:i A') }}</dd>
                    </dl>

                    <div class="mt-4">
                        <form action="{{ route('room-assignments.destroy', $roomAssignment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this assignment?')">
                                <i class="fas fa-trash"></i> Delete Assignment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
