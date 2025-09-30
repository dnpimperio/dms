<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Utility Type') }}
            </h2>
            <a href="{{ route('admin.utility-types.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.utility-types.store') }}" x-data="utilityTypeForm()">
                        @csrf

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block font-medium text-sm text-gray-700">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="name" 
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Electricity, Water, Gas"
                                   required autofocus />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit of Measurement -->
                        <div class="mb-6">
                            <label for="unit_of_measurement" class="block font-medium text-sm text-gray-700">
                                {{ __('Unit of Measurement') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="unit_of_measurement" 
                                    name="unit_of_measurement" 
                                    x-model="selectedUnit"
                                    @change="handleUnitChange()"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                    required>
                                <option value="">{{ __('Select a unit of measurement') }}</option>
                                <option value="Kilowatt-hour (kWh)" {{ old('unit_of_measurement') == 'Kilowatt-hour (kWh)' ? 'selected' : '' }}>
                                    Kilowatt-hour (kWh)
                                </option>
                                <option value="kW" {{ old('unit_of_measurement') == 'kW' ? 'selected' : '' }}>
                                    kW
                                </option>
                                <option value="Cubic meter (m³)" {{ old('unit_of_measurement') == 'Cubic meter (m³)' ? 'selected' : '' }}>
                                    Cubic meter (m³)
                                </option>
                                <option value="Gallons" {{ old('unit_of_measurement') == 'Gallons' ? 'selected' : '' }}>
                                    Gallons
                                </option>
                                <option value="Cubic foot (ft³)" {{ old('unit_of_measurement') == 'Cubic foot (ft³)' ? 'selected' : '' }}>
                                    Cubic foot (ft³)
                                </option>
                                <option value="CCF (Centum Cubic Feet)" {{ old('unit_of_measurement') == 'CCF (Centum Cubic Feet)' ? 'selected' : '' }}>
                                    CCF (Centum Cubic Feet)
                                </option>
                                <option value="HCF (Hundred Cubic Feet)" {{ old('unit_of_measurement') == 'HCF (Hundred Cubic Feet)' ? 'selected' : '' }}>
                                    HCF (Hundred Cubic Feet)
                                </option>
                                <option value="Therm" {{ old('unit_of_measurement') == 'Therm' ? 'selected' : '' }}>
                                    Therm
                                </option>
                                <option value="Other" {{ old('unit_of_measurement') && !in_array(old('unit_of_measurement'), ['Kilowatt-hour (kWh)', 'kW', 'Cubic meter (m³)', 'Gallons', 'Cubic foot (ft³)', 'CCF (Centum Cubic Feet)', 'HCF (Hundred Cubic Feet)', 'Therm']) ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                            @error('unit_of_measurement')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Custom Unit Input (shown when "Other" is selected) -->
                        <div class="mb-6" x-show="showCustomUnit" x-transition style="display: none;">
                            <label for="custom_unit" class="block font-medium text-sm text-gray-700">
                                {{ __('Custom Unit of Measurement') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="custom_unit" 
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                   type="text" 
                                   name="custom_unit"
                                   x-model="customUnit"
                                   value="{{ old('custom_unit') }}" 
                                   placeholder="e.g., BTU, MCF, etc."
                                   :required="showCustomUnit" />
                            @error('custom_unit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block font-medium text-sm text-gray-700">
                                {{ __('Description') }}
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                      placeholder="Optional description of this utility type">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block font-medium text-sm text-gray-700">
                                {{ __('Status') }}
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    {{ __('Active') }}
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    {{ __('Inactive') }}
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('admin.utility-types.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Create Utility Type') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function utilityTypeForm() {
            return {
                selectedUnit: '{{ old('unit_of_measurement', '') }}',
                customUnit: '{{ old('custom_unit', '') }}',
                showCustomUnit: {{ old('unit_of_measurement') && !in_array(old('unit_of_measurement'), ['Kilowatt-hour (kWh)', 'kW', 'Cubic meter (m³)', 'Gallons', 'Cubic foot (ft³)', 'CCF (Centum Cubic Feet)', 'HCF (Hundred Cubic Feet)', 'Therm']) ? 'true' : 'false' }},
                
                handleUnitChange() {
                    this.showCustomUnit = this.selectedUnit === 'Other';
                    if (!this.showCustomUnit) {
                        this.customUnit = '';
                    }
                }
            }
        }
    </script>
</x-app-layout>
