@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Room Assignment</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('room-assignments.update', $roomAssignment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="room_id" class="form-label">Room</label>
                            <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            {{ (old('room_id', $roomAssignment->room_id) == $room->id) ? 'selected' : '' }}>
                                        {{ $room->room_number }} (₱{{ number_format($room->price, 2) }}/month)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant</label>
                            <select name="tenant_id" id="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror" required>
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}"
                                            {{ (old('tenant_id', $roomAssignment->tenant_id) == $tenant->id) ? 'selected' : '' }}>
                                        {{ $tenant->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $roomAssignment->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $roomAssignment->end_date?->format('Y-m-d')) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monthly_rent" class="form-label">Monthly Rent</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="monthly_rent" id="monthly_rent" 
                                       class="form-control @error('monthly_rent') is-invalid @enderror"
                                       value="{{ old('monthly_rent', $roomAssignment->monthly_rent) }}" step="0.01" required>
                            </div>
                            @error('monthly_rent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(['pending', 'active', 'completed'] as $status)
                                    <option value="{{ $status }}" 
                                            {{ (old('status', $roomAssignment->status) == $status) ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $roomAssignment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('room-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
