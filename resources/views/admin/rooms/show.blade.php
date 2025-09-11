<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Room Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit Room
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Room Information</h3>
                                <dl class="mt-4 grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Room Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $room->room_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $room->type }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Rate</dt>
                                        <dd class="mt-1 text-sm text-gray-900">₱{{ number_format($room->rate, 2) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Occupancy Details</h3>
                                <dl class="mt-4 grid grid-cols-1 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $room->capacity }} Person(s)</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Current Occupants</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $room->current_occupants }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 
                                                   ($room->status === 'occupied' ? 'bg-blue-100 text-blue-800' : 
                                                   ($room->status === 'reserved' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    @if($room->description)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Description</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $room->description }}</p>
                        </div>
                    @endif

                    <!-- Tenant Assignment Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Room Assignments</h3>
                            @if($room->status === 'available')
                                <a href="{{ route('room-assignments.create', ['room_id' => $room->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-plus mr-2"></i> Assign Tenant
                                </a>
                            @endif
                        </div>

                        @if($room->currentAssignments->count() > 0)
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($room->currentAssignments as $assignment)
                                        <li class="px-4 py-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $assignment->tenant->full_name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            From: {{ $assignment->start_date->format('M d, Y') }}
                                                            @if($assignment->end_date)
                                                                to {{ $assignment->end_date->format('M d, Y') }}
                                                            @endif
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            Monthly Rent: ₱{{ number_format($assignment->monthly_rent, 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('room-assignments.show', $assignment) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        View Details
                                                    </a>
                                                    @if($assignment->status === 'active')
                                                        <form action="{{ route('room-assignments.end', $assignment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" onclick="return confirm('Are you sure you want to end this assignment?')"
                                                                    class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                End Assignment
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No current assignments for this room.</p>
                        @endif
                    </div>

                    <!-- Quick Status Update -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Status Update</h3>
                        <form action="{{ route('admin.rooms.update-status', $room) }}" method="POST" class="inline-flex space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="reserved" {{ $room->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            </select>
                            <x-primary-button>
                                {{ __('Update Status') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
