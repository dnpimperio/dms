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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>

                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $tenant->first_name)" required 
                                                  pattern="[a-zA-Z\s]+" title="Only letters and spaces allowed"
                                                  oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                                    <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name', $tenant->middle_name)" 
                                                  pattern="[a-zA-Z\s]*" title="Only letters and spaces allowed"
                                                  oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $tenant->last_name)" required 
                                                  pattern="[a-zA-Z\s]+" title="Only letters and spaces allowed"
                                                  oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" :value="__('Birth Date')" />
                                    <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $tenant->birth_date?->format('Y-m-d'))" required 
                                                  max="{{ now()->subYears(18)->format('Y-m-d') }}" />
                                    <p class="mt-1 text-sm text-gray-500">Must be at least 18 years old</p>
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $tenant->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $tenant->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $tenant->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="civil_status" :value="__('Civil Status')" />
                                    <select id="civil_status" name="civil_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="single" {{ old('civil_status', $tenant->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('civil_status', $tenant->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('civil_status', $tenant->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('civil_status', $tenant->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="separated" {{ old('civil_status', $tenant->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nationality" :value="__('Nationality')" />
                                    <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality', $tenant->nationality)" required />
                                    <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="occupation" :value="__('Occupation')" />
                                    <x-text-input id="occupation" class="block mt-1 w-full" type="text" name="occupation" :value="old('occupation', $tenant->occupation)" required />
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Contact Details Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Contact Details</h3>

                                <div>
                                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $tenant->phone_number)" required 
                                                  pattern="[0-9]{10,11}" maxlength="11" 
                                                  placeholder="10-11 digits (e.g., 09171234567)" 
                                                  oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="alternative_phone" :value="__('Alternative Phone')" />
                                    <x-text-input id="alternative_phone" class="block mt-1 w-full" type="text" name="alternative_phone" :value="old('alternative_phone', $tenant->alternative_phone)" 
                                                  pattern="[0-9]{10,11}" maxlength="11" 
                                                  placeholder="10-11 digits (optional)" 
                                                  oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <x-input-error :messages="$errors->get('alternative_phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="personal_email" :value="__('Personal Email')" />
                                    <x-text-input id="personal_email" class="block mt-1 w-full" type="email" name="personal_email" :value="old('personal_email', $tenant->personal_email)" required />
                                    <x-input-error :messages="$errors->get('personal_email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="permanent_address" :value="__('Permanent Address')" />
                                    <textarea id="permanent_address" name="permanent_address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('permanent_address', $tenant->permanent_address) }}</textarea>
                                    <x-input-error :messages="$errors->get('permanent_address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="current_address" :value="__('Current Address')" />
                                    <textarea id="current_address" name="current_address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('current_address', $tenant->current_address) }}</textarea>
                                    <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="remarks" :value="__('Remarks')" />
                                    <textarea id="remarks" name="remarks" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Additional notes or comments">{{ old('remarks', $tenant->remarks) }}</textarea>
                                    <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Update Tenant') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
