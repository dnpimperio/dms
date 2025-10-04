<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Utility Reading Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.utility-readings.edit', $utilityReading) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit Reading
                </a>
                <a href="{{ route('admin.utility-readings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                        <!-- Reading Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Reading Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Room</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Room {{ $utilityReading->room ? $utilityReading->room->room_number : 'Unknown' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Utility Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $utilityReading->utilityType ? $utilityReading->utilityType->name : 'Unknown' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reading Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->reading_date ? $utilityReading->reading_date->format('M d, Y') : 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Recorded By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->recordedBy ? $utilityReading->recordedBy->name : 'Unknown' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Consumption Data -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Consumption Data</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Previous Reading</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ number_format($utilityReading->previous_reading, 2) }}
                                        @if($utilityReading->utilityType)
                                            {{ $utilityReading->utilityType->unit }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Reading</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ number_format($utilityReading->current_reading, 2) }}
                                        @if($utilityReading->utilityType)
                                            {{ $utilityReading->utilityType->unit }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Consumption</dt>
                                    <dd class="mt-1 text-2xl font-bold text-green-600">
                                        {{ number_format($utilityReading->consumption, 2) }}
                                        @if($utilityReading->utilityType)
                                            {{ $utilityReading->utilityType->unit }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estimated Cost</dt>
                                    <dd class="mt-1 text-xl font-bold text-blue-600">
                                        ₱{{ number_format($utilityReading->calculateCost(), 2) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($utilityReading->notes)
                    <!-- Notes -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <p class="text-sm text-gray-700">{{ $utilityReading->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Billing Status -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Bill ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($utilityReading->bill_id)
                                        <a href="{{ route('admin.bills.show', $utilityReading->bill_id) }}" class="text-blue-600 hover:text-blue-500 underline">
                                            #{{ $utilityReading->bill_id }}
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Not Billed Yet
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($utilityReading->bill_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Billed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    @if($utilityReading->room)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Room Information</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Room Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->room->room_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Room Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($utilityReading->room->type) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->room->capacity }} person(s)</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Record Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->created_at->format('M d, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $utilityReading->updated_at->format('M d, Y g:i A') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>