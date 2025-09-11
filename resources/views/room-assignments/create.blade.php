@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create Room Assignment</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('room-assignments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="room_id" class="form-label">Room</label>
                            <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            data-price="{{ $room->price }}"
                                            {{ (old('room_id', $selectedRoom?->id) == $room->id) ? 'selected' : '' }}>
                                        Room {{ $room->room_number }} - {{ $room->type }}
                                        (₱{{ number_format($room->price, 2) }}/month)
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
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->full_name }} ({{ $tenant->phone }})
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
                                   value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}">
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
                                       value="{{ old('monthly_rent') }}" step="0.01" required>
                            </div>
                            @error('monthly_rent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('room-assignments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const rentInput = document.getElementById('monthly_rent');

    // Auto-fill monthly rent when room is selected
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            rentInput.value = selectedOption.dataset.price;
        }
    });

    // Set initial rent if room is pre-selected
    if (roomSelect.value) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        rentInput.value = selectedOption.dataset.price;
    }
});
</script>
@endpush
