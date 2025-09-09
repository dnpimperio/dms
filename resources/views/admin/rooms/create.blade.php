<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Room Number -->
                            <div>
                                <x-input-label for="room_number" :value="__('Room Number')" />
                                <x-text-input id="room_number" class="block mt-1 w-full" type="text" name="room_number" :value="old('room_number')" required />
                                <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Room Type')" />
                                <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')" required />
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Rate -->
                            <div>
                                <x-input-label for="rate" :value="__('Rate (₱)')" />
                                <x-text-input id="rate" class="block mt-1 w-full" type="number" step="0.01" name="rate" :value="old('rate')" required />
                                <x-input-error :messages="$errors->get('rate')" class="mt-2" />
                            </div>

                            <!-- Capacity -->
                            <div>
                                <x-input-label for="capacity" :value="__('Capacity')" />
                                <select id="capacity" name="capacity" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="1">1 Person</option>
                                    <option value="2" selected>2 Persons</option>
                                </select>
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="available">Available</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Under Maintenance</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Room') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
