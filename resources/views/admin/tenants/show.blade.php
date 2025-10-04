<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tenant Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit Tenant
                </a>
                <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->full_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->birth_date->format('F d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($tenant->gender) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->nationality }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->occupation }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Civil Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->civil_status }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Contact Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Contact Details</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->phone_number }}</dd>
                                </div>
                                @if($tenant->alternative_phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alternative Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->alternative_phone }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Personal Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->personal_email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Login Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $tenant->user ? $tenant->user->email : 'No user account linked' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Permanent Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->permanent_address }}</dd>
                                </div>
                                @if($tenant->current_address)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->current_address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- ID Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">ID Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->id_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tenant->id_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Image</dt>
                                    <dd class="mt-1">
                                        <img src="{{ Storage::url($tenant->id_image_path) }}" alt="ID Image" class="max-w-md rounded-lg shadow-md">
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Emergency Contacts -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Emergency Contacts</h3>
                            @foreach($tenant->emergencyContacts as $contact)
                                <div class="border p-4 rounded-md">
                                    <dl class="grid grid-cols-1 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->relationship }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->phone_number }}</dd>
                                        </div>
                                        @if($contact->alternative_phone)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Alternative Phone</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $contact->alternative_phone }}</dd>
                                            </div>
                                        @endif
                                        @if($contact->email)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $contact->email }}</dd>
                                            </div>
                                        @endif
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $contact->address }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($tenant->remarks)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Remarks</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $tenant->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
