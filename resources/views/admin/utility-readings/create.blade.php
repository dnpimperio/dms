<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Utility Reading') }}
            </h2>
            <a href="{{ route('admin.utility-readings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.utility-readings.store') }}" class="space-y-6">
                        @csrf

                        <!-- Room -->
                        <div>
                            <x-input-label for="room_id" :value="__('Room')" />
                            <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }} - {{ $room->tenant ? $room->tenant->first_name . ' ' . $room->tenant->last_name : 'Vacant' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                        </div>

                        <!-- Utility Type -->
                        <div>
                            <x-input-label for="utility_type_id" :value="__('Utility Type')" />
                            <select id="utility_type_id" name="utility_type_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Utility Type</option>
                                @foreach($utilityTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('utility_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->unit }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('utility_type_id')" class="mt-2" />
                        </div>

                        <!-- Previous Reading -->
                        <div>
                            <x-input-label for="previous_reading" :value="__('Previous Reading')" />
                            <x-text-input id="previous_reading" class="block mt-1 w-full" type="number" name="previous_reading" :value="old('previous_reading')" step="0.01" />
                            <x-input-error :messages="$errors->get('previous_reading')" class="mt-2" />
                        </div>

                        <!-- Current Reading -->
                        <div>
                            <x-input-label for="reading_value" :value="__('Current Reading')" />
                            <x-text-input id="reading_value" class="block mt-1 w-full" type="number" name="reading_value" :value="old('reading_value')" step="0.01" required />
                            <x-input-error :messages="$errors->get('reading_value')" class="mt-2" />
                        </div>

                        <!-- Consumption (Auto-calculated) -->
                        <div>
                            <x-input-label for="consumption" :value="__('Consumption')" />
                            <x-text-input id="consumption" class="block mt-1 w-full bg-gray-100" type="number" name="consumption" :value="old('consumption')" step="0.01" readonly />
                            <p class="text-sm text-gray-600 mt-1">This will be automatically calculated (Current Reading - Previous Reading)</p>
                            <x-input-error :messages="$errors->get('consumption')" class="mt-2" />
                        </div>

                        <!-- Reading Date -->
                        <div>
                            <x-input-label for="reading_date" :value="__('Reading Date')" />
                            <x-text-input id="reading_date" class="block mt-1 w-full" type="date" name="reading_date" :value="old('reading_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('reading_date')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <a href="{{ route('admin.utility-readings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Reading') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate consumption when readings change
        document.getElementById('reading_value').addEventListener('input', calculateConsumption);
        document.getElementById('previous_reading').addEventListener('input', calculateConsumption);

        function calculateConsumption() {
            const currentReading = parseFloat(document.getElementById('reading_value').value) || 0;
            const previousReading = parseFloat(document.getElementById('previous_reading').value) || 0;
            const consumption = currentReading - previousReading;
            document.getElementById('consumption').value = consumption >= 0 ? consumption.toFixed(2) : '';
        }
    </script>
</x-app-layout>