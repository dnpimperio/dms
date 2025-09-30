<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Utility Rate') }}
            </h2>
            <a href="{{ route('admin.utility-rates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.utility-rates.store') }}">
                        @csrf

                        <!-- Utility Type -->
                        <div class="mb-4">
                            <label for="utility_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Utility Type <span class="text-red-500">*</span>
                            </label>
                            <select name="utility_type_id" 
                                    id="utility_type_id" 
                                    class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('utility_type_id') border-red-500 focus:border-red-500 @else border-gray-300 focus:border-indigo-300 @enderror"
                                    required>
                                <option value="">Select Utility Type</option>
                                @foreach($utilityTypes as $utilityType)
                                    <option value="{{ $utilityType->id }}" 
                                            {{ old('utility_type_id', request('utility_type')) == $utilityType->id ? 'selected' : '' }}>
                                        {{ $utilityType->name }} ({{ $utilityType->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('utility_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rate per Unit -->
                        <div class="mb-4">
                            <label for="rate_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Rate per Unit <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="rate_per_unit" 
                                       id="rate_per_unit" 
                                       step="0.0001"
                                       min="0"
                                       value="{{ old('rate_per_unit') }}"
                                       class="w-full pl-7 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('rate_per_unit') border-red-500 focus:border-red-500 @else border-gray-300 focus:border-indigo-300 @enderror"
                                       placeholder="0.0000"
                                       required>
                            </div>
                            @error('rate_per_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Effective From -->
                        <div class="mb-4">
                            <label for="effective_from" class="block text-sm font-medium text-gray-700 mb-2">
                                Effective From <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="effective_from" 
                                   id="effective_from" 
                                   value="{{ old('effective_from', now()->format('Y-m-d')) }}"
                                   class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('effective_from') border-red-500 focus:border-red-500 @else border-gray-300 focus:border-indigo-300 @enderror"
                                   required>
                            @error('effective_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Effective Until -->
                        <div class="mb-6">
                            <label for="effective_until" class="block text-sm font-medium text-gray-700 mb-2">
                                Effective Until
                            </label>
                            <input type="date" 
                                   name="effective_until" 
                                   id="effective_until" 
                                   value="{{ old('effective_until') }}"
                                   class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('effective_until') border-red-500 focus:border-red-500 @else border-gray-300 focus:border-indigo-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Leave empty for ongoing rate</p>
                            @error('effective_until')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.utility-rates.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Utility Rate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>