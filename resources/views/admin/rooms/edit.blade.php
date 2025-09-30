<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Room') }}
            </h2>
            <a href="{{ route('admin.rooms.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Rooms') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Room Number -->
                            <div class="mb-6">
                                <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Room Number') }}
                                </label>
                                <input type="text" 
                                       id="room_number" 
                                       name="room_number" 
                                       value="{{ old('room_number', $room->room_number) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('room_number') border-red-300 @enderror"
                                       required>
                                @error('room_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Room Type -->
                            <div class="mb-6">
                                <label for="room_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Room Type') }}
                                </label>
                                <select id="room_type" 
                                        name="room_type" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('room_type') border-red-300 @enderror"
                                        required>
                                    <option value="">Select Room Type</option>
                                    <option value="single" {{ old('room_type', $room->room_type) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="double" {{ old('room_type', $room->room_type) == 'double' ? 'selected' : '' }}>Double</option>
                                    <option value="triple" {{ old('room_type', $room->room_type) == 'triple' ? 'selected' : '' }}>Triple</option>
                                    <option value="quad" {{ old('room_type', $room->room_type) == 'quad' ? 'selected' : '' }}>Quad</option>
                                    <option value="suite" {{ old('room_type', $room->room_type) == 'suite' ? 'selected' : '' }}>Suite</option>
                                </select>
                                @error('room_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="mb-6">
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Capacity') }}
                                </label>
                                <input type="number" 
                                       id="capacity" 
                                       name="capacity" 
                                       value="{{ old('capacity', $room->capacity) }}" 
                                       min="1"
                                       max="10"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('capacity') border-red-300 @enderror"
                                       required>
                                @error('capacity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="mb-6">
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Price (₱)') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $room->price) }}" 
                                           step="0.01"
                                           min="0"
                                           class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-300 @enderror"
                                           required>
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Status') }}
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror">
                                    <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                    <option value="reserved" {{ old('status', $room->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Description') }}
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amenities -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Amenities') }}
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @php
                                    $availableAmenities = [
                                        'air_conditioning' => 'Air Conditioning',
                                        'wifi' => 'WiFi',
                                        'private_bathroom' => 'Private Bathroom',
                                        'shared_bathroom' => 'Shared Bathroom',
                                        'desk' => 'Study Desk',
                                        'wardrobe' => 'Wardrobe',
                                        'mini_fridge' => 'Mini Fridge',
                                        'balcony' => 'Balcony',
                                        'tv' => 'Television',
                                        'laundry' => 'Laundry Access'
                                    ];
                                    $selectedAmenities = old('amenities', $room->amenities ?? []);
                                @endphp

                                @foreach($availableAmenities as $key => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="amenity_{{ $key }}" 
                                               name="amenities[]" 
                                               value="{{ $key }}"
                                               {{ in_array($key, $selectedAmenities) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="amenity_{{ $key }}" class="ml-2 block text-sm text-gray-900">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('amenities')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Update Room') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
