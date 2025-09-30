<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Tenant') }}
            </h2>
            <a href="{{ route('admin.tenants.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Tenants') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="mb-6">
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('First Name') }}
                                </label>
                                <input type="text" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name', $tenant->first_name) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-300 @enderror"
                                       required>
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="mb-6">
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Last Name') }}
                                </label>
                                <input type="text" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name', $tenant->last_name) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-300 @enderror"
                                       required>
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Email Address') }}
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $tenant->email) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-6">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Phone Number') }}
                                </label>
                                <input type="text" 
                                       id="phone_number" 
                                       name="phone_number" 
                                       value="{{ old('phone_number', $tenant->phone_number) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone_number') border-red-300 @enderror">
                                @error('phone_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-6">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Date of Birth') }}
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $tenant->date_of_birth?->format('Y-m-d')) }}" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_birth') border-red-300 @enderror">
                                @error('date_of_birth')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-6">
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Gender') }}
                                </label>
                                <select id="gender" 
                                        name="gender" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-300 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $tenant->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $tenant->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $tenant->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Address') }}
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-300 @enderror">{{ old('address', $tenant->address) }}</textarea>
                            @error('address')
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
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="moved_out" {{ old('status', $tenant->status) == 'moved_out' ? 'selected' : '' }}>Moved Out</option>
                            </select>
                            @error('status')
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
                                {{ __('Update Tenant') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
