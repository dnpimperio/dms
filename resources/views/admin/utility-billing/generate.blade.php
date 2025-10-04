<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Generate Utility Bills') }}
            </h2>
            <a href="{{ route('admin.utility-billing.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Utility Billing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Form for generating utility bills -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bill Generation Parameters</h3>
                    
                    <form method="POST" action="{{ route('admin.utility-billing.generate.post') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Billing Month -->
                            <div>
                                <x-input-label for="billing_month" :value="__('Billing Month')" />
                                <select id="billing_month" name="billing_month" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Billing Month</option>
                                    @foreach($availableMonths as $month)
                                        <option value="{{ $month['value'] }}" {{ old('billing_month') == $month['value'] ? 'selected' : '' }}>
                                            {{ $month['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('billing_month')" class="mt-2" />
                            </div>

                            <!-- Utility Type -->
                            <div>
                                <x-input-label for="utility_type_id" :value="__('Utility Type')" />
                                <select id="utility_type_id" name="utility_type_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Utility Types</option>
                                    @foreach($utilityTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('utility_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('utility_type_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Room Selection -->
                            <div>
                                <x-input-label for="room_ids" :value="__('Rooms')" />
                                <div class="mt-1 space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="select_all_rooms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Select All Rooms</span>
                                    </label>
                                    <hr class="my-2">
                                    @foreach($rooms as $room)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="room_ids[]" value="{{ $room->id }}" class="room-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($room->id, old('room_ids', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $room->room_number }} - 
                                                @if($room->activeAssignment && $room->activeAssignment->tenant)
                                                    {{ $room->activeAssignment->tenant->first_name }} {{ $room->activeAssignment->tenant->last_name }}
                                                @else
                                                    Vacant
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('room_ids')" class="mt-2" />
                            </div>

                            <!-- Bill Options -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="bill_date" :value="__('Bill Date')" />
                                    <x-text-input id="bill_date" class="block mt-1 w-full" type="date" name="bill_date" :value="old('bill_date', date('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('bill_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="due_date" :value="__('Due Date')" />
                                    <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', date('Y-m-d', strtotime('+30 days')))" required />
                                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="include_zero_consumption" name="include_zero_consumption" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('include_zero_consumption') ? 'checked' : '' }}>
                                    <label for="include_zero_consumption" class="ml-2 text-sm text-gray-700">
                                        Include rooms with zero consumption
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="preview_only" name="preview_only" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('preview_only') ? 'checked' : '' }}>
                                    <label for="preview_only" class="ml-2 text-sm text-gray-700">
                                        Preview only (don't save bills)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.utility-billing.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Generate Bills') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">💡 Bill Generation Information</h3>
                    <div class="text-sm text-blue-800 space-y-2">
                        <p><strong>How it works:</strong></p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li>Select the month for which you want to generate utility bills</li>
                            <li>Choose specific utility types or generate for all types</li>
                            <li>Select specific rooms or all rooms</li>
                            <li>The system will calculate consumption based on utility readings</li>
                            <li>Bills will be generated using the current utility rates</li>
                        </ul>
                        <p class="mt-4"><strong>Note:</strong> Only rooms with utility readings for the selected month will have bills generated (unless you include zero consumption).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle select all rooms functionality
        document.getElementById('select_all_rooms').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.room-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update select all checkbox when individual checkboxes change
        document.querySelectorAll('.room-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.room-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.room-checkbox:checked');
                const selectAllCheckbox = document.getElementById('select_all_rooms');
                
                if (checkedCheckboxes.length === allCheckboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCheckboxes.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            });
        });

        // Auto-calculate due date (30 days from bill date)
        document.getElementById('bill_date').addEventListener('change', function() {
            const billDate = new Date(this.value);
            if (billDate) {
                const dueDate = new Date(billDate);
                dueDate.setDate(dueDate.getDate() + 30);
                document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];
            }
        });
    </script>
</x-app-layout>