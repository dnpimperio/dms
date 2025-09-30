<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tenant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>

                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $tenant->first_name)" 
                                        pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed" 
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                                    <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name', $tenant->middle_name)" 
                                        pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed" 
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $tenant->last_name)" 
                                        pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed" 
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" :value="__('Birth Date')" />
                                    <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $tenant->birth_date->format('Y-m-d'))" 
                                        max="{{ date('Y-m-d', strtotime('-18 years')) }}" required />
                                    <p class="text-sm text-gray-600 mt-1">Must be at least 18 years old</p>
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <div class="block mt-1 w-full p-2 bg-gray-100 text-gray-700 rounded-md border border-gray-300">
                                        Female
                                    </div>
                                    <input type="hidden" name="gender" value="female">
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
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

                                <div>
                                    <x-input-label for="university" :value="__('University')" />
                                    <x-text-input id="university" class="block mt-1 w-full" type="text" name="university" :value="old('university', $tenant->university ?? '')" required />
                                    <x-input-error :messages="$errors->get('university')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="course" :value="__('Course')" />
                                    <x-text-input id="course" class="block mt-1 w-full" type="text" name="course" :value="old('course', $tenant->course ?? '')" required />
                                    <x-input-error :messages="$errors->get('course')" class="mt-2" />
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
                                    <x-input-label for="provincial_address" :value="__('Provincial Address')" />
                                    <textarea id="provincial_address" name="provincial_address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('provincial_address', $tenant->provincial_address ?? $tenant->permanent_address ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('provincial_address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="current_address" :value="__('Current Address')" />
                                    <textarea id="current_address" name="current_address" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('current_address', $tenant->current_address) }}</textarea>
                                    <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
                                </div>
                            </div>

                            <!-- ID Verification Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">ID Verification</h3>

                                <div>
                                    <x-input-label for="id_type" :value="__('ID Type')" />
                                    <select id="id_type" name="id_type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Select ID Type</option>
                                        <option value="drivers_license" {{ old('id_type', $tenant->id_type) === 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                        <option value="passport" {{ old('id_type', $tenant->id_type) === 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="national_id" {{ old('id_type', $tenant->id_type) === 'national_id' ? 'selected' : '' }}>National ID</option>
                                        <option value="voters_id" {{ old('id_type', $tenant->id_type) === 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                                        <option value="tin_id" {{ old('id_type', $tenant->id_type) === 'tin_id' ? 'selected' : '' }}>TIN ID</option>
                                        <option value="sss_id" {{ old('id_type', $tenant->id_type) === 'sss_id' ? 'selected' : '' }}>SSS ID</option>
                                        <option value="philhealth_id" {{ old('id_type', $tenant->id_type) === 'philhealth_id' ? 'selected' : '' }}>PhilHealth ID</option>
                                        <option value="pag_ibig_id" {{ old('id_type', $tenant->id_type) === 'pag_ibig_id' ? 'selected' : '' }}>Pag-IBIG ID</option>
                                        <option value="postal_id" {{ old('id_type', $tenant->id_type) === 'postal_id' ? 'selected' : '' }}>Postal ID</option>
                                        <option value="barangay_id" {{ old('id_type', $tenant->id_type) === 'barangay_id' ? 'selected' : '' }}>Barangay ID</option>
                                        <option value="senior_citizen_id" {{ old('id_type', $tenant->id_type) === 'senior_citizen_id' ? 'selected' : '' }}>Senior Citizen ID</option>
                                        <option value="pwd_id" {{ old('id_type', $tenant->id_type) === 'pwd_id' ? 'selected' : '' }}>PWD ID</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('id_type')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="id_number" :value="__('ID Number')" />
                                    <x-text-input id="id_number" class="block mt-1 w-full" type="text" name="id_number" :value="old('id_number', $tenant->id_number)" required />
                                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="id_image" :value="__('ID Image')" />
                                    @if($tenant->id_image)
                                        <div class="mt-2 mb-2">
                                            <img src="{{ Storage::url($tenant->id_image) }}" alt="ID Image" class="max-w-xs">
                                        </div>
                                    @endif
                                    <input type="file" id="id_image" name="id_image" class="block mt-1 w-full" accept="image/*" />
                                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep the current image</p>
                                    <x-input-error :messages="$errors->get('id_image')" class="mt-2" />
                                </div>
                            </div>

                            <!-- User Account Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">User Account</h3>

                                <div>
                                    <x-input-label for="email" :value="__('Login Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $tenant->user->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep the current password</p>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="remarks" :value="__('Remarks')" />
                                    <textarea id="remarks" name="remarks" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('remarks', $tenant->remarks) }}</textarea>
                                    <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contacts Section -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contacts</h3>
                            <div id="emergency-contacts">
                                @foreach($tenant->emergencyContacts as $index => $contact)
                                    <div class="border p-4 rounded-md mb-4">
                                        <input type="hidden" name="emergency_contacts[{{ $index }}][id]" value="{{ $contact->id }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][name]" :value="__('Name')" />
                                                <x-text-input id="emergency_contacts[{{ $index }}][name]" class="block mt-1 w-full" type="text" name="emergency_contacts[{{ $index }}][name]" 
                                                    :value="old('emergency_contacts.'.$index.'.name', $contact->name)" 
                                                    pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed" 
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required />
                                            </div>

                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][relationship]" :value="__('Relationship')" />
                                                <x-text-input id="emergency_contacts[{{ $index }}][relationship]" class="block mt-1 w-full" type="text" name="emergency_contacts[{{ $index }}][relationship]" :value="old('emergency_contacts.'.$index.'.relationship', $contact->relationship)" required />
                                            </div>

                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][phone_number]" :value="__('Phone Number')" />
                                                <x-text-input id="emergency_contacts[{{ $index }}][phone_number]" class="block mt-1 w-full" type="text" name="emergency_contacts[{{ $index }}][phone_number]" :value="old('emergency_contacts.'.$index.'.phone_number', $contact->phone_number)" required />
                                            </div>

                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][alternative_phone]" :value="__('Alternative Phone')" />
                                                <x-text-input id="emergency_contacts[{{ $index }}][alternative_phone]" class="block mt-1 w-full" type="text" name="emergency_contacts[{{ $index }}][alternative_phone]" :value="old('emergency_contacts.'.$index.'.alternative_phone', $contact->alternative_phone)" />
                                            </div>

                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][email]" :value="__('Email')" />
                                                <x-text-input id="emergency_contacts[{{ $index }}][email]" class="block mt-1 w-full" type="email" name="emergency_contacts[{{ $index }}][email]" :value="old('emergency_contacts.'.$index.'.email', $contact->email)" />
                                            </div>

                                            <div>
                                                <x-input-label for="emergency_contacts[{{ $index }}][address]" :value="__('Address')" />
                                                <textarea id="emergency_contacts[{{ $index }}][address]" name="emergency_contacts[{{ $index }}][address]" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('emergency_contacts.'.$index.'.address', $contact->address) }}</textarea>
                                            </div>
                                        </div>
                                        @if(!$loop->first)
                                            <button type="button" onclick="this.parentElement.remove()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Remove Contact
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addEmergencyContact()" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                Add Another Emergency Contact
                            </button>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Tenant') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let contactCount = {{ count($tenant->emergencyContacts) }};

        function addEmergencyContact() {
            const container = document.getElementById('emergency-contacts');
            const template = `
                <div class="border p-4 rounded-md mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="emergency_contacts[${contactCount}][name]" class="block font-medium text-sm text-gray-700">Name</label>
                            <input id="emergency_contacts[${contactCount}][name]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="emergency_contacts[${contactCount}][name]" 
                                pattern="[a-zA-Z\\s]+" title="Only letters and spaces are allowed" 
                                oninput="this.value = this.value.replace(/[^a-zA-Z\\s]/g, '')" required />
                        </div>

                        <div>
                            <label for="emergency_contacts[${contactCount}][relationship]" class="block font-medium text-sm text-gray-700">Relationship</label>
                            <input id="emergency_contacts[${contactCount}][relationship]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="emergency_contacts[${contactCount}][relationship]" required />
                        </div>

                        <div>
                            <label for="emergency_contacts[${contactCount}][phone_number]" class="block font-medium text-sm text-gray-700">Phone Number</label>
                            <input id="emergency_contacts[${contactCount}][phone_number]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="emergency_contacts[${contactCount}][phone_number]" 
                                pattern="[0-9+\\-\\s\\(\\)]+" title="Only numbers, +, -, spaces, and parentheses allowed"
                                oninput="this.value = this.value.replace(/[^0-9+\\-\\s\\(\\)]/g, '')" required />
                        </div>

                        <div>
                            <label for="emergency_contacts[${contactCount}][alternative_phone]" class="block font-medium text-sm text-gray-700">Alternative Phone</label>
                            <input id="emergency_contacts[${contactCount}][alternative_phone]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="emergency_contacts[${contactCount}][alternative_phone]" 
                                pattern="[0-9+\\-\\s\\(\\)]+" title="Only numbers, +, -, spaces, and parentheses allowed"
                                oninput="this.value = this.value.replace(/[^0-9+\\-\\s\\(\\)]/g, '')" />
                        </div>

                        <div>
                            <label for="emergency_contacts[${contactCount}][email]" class="block font-medium text-sm text-gray-700">Email</label>
                            <input id="emergency_contacts[${contactCount}][email]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="email" name="emergency_contacts[${contactCount}][email]" />
                        </div>

                        <div>
                            <label for="emergency_contacts[${contactCount}][address]" class="block font-medium text-sm text-gray-700">Address</label>
                            <textarea id="emergency_contacts[${contactCount}][address]" name="emergency_contacts[${contactCount}][address]" rows="2" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="mt-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Remove Contact
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            contactCount++;
        }
    </script>
</x-app-layout>
