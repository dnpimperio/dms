<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bill Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Bill Information</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <dt class="text-sm font-medium text-gray-500">Bill Date</dt>
                                <dd class="text-sm text-gray-900">{{ $bill->bill_date->format('M d, Y') }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                                <dd class="text-sm text-gray-900">{{ $bill->due_date->format('M d, Y') }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $bill->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($bill->status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                    </span>
                                </dd>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Tenant Information</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $bill->tenant->name }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Room Number</dt>
                                <dd class="text-sm text-gray-900">{{ $bill->room->room_number }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Charges Breakdown</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dl class="grid grid-cols-2 gap-4">
                                <dt class="text-sm font-medium text-gray-500">Monthly Rate</dt>
                                <dd class="text-sm text-gray-900">₱{{ number_format($bill->room_rate, 2) }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Electricity</dt>
                                <dd class="text-sm text-gray-900">₱{{ number_format($bill->electricity, 2) }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Water</dt>
                                <dd class="text-sm text-gray-900">₱{{ number_format($bill->water, 2) }}</dd>

                                @if($bill->other_charges > 0)
                                    <dt class="text-sm font-medium text-gray-500">Other Charges</dt>
                                    <dd class="text-sm text-gray-900">₱{{ number_format($bill->other_charges, 2) }}</dd>

                                    @if($bill->other_charges_description)
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="text-sm text-gray-900">{{ $bill->other_charges_description }}</dd>
                                    @endif
                                @endif

                                <dt class="text-sm font-medium text-gray-500 pt-4 border-t">Total Amount</dt>
                                <dd class="text-sm font-bold text-gray-900 pt-4 border-t">₱{{ number_format($bill->total_amount, 2) }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                                <dd class="text-sm text-gray-900">₱{{ number_format($bill->amount_paid, 2) }}</dd>

                                <dt class="text-sm font-medium text-gray-500">Balance</dt>
                                <dd class="text-sm font-bold text-{{ $bill->total_amount - $bill->amount_paid > 0 ? 'red' : 'green' }}-600">
                                    ₱{{ number_format($bill->total_amount - $bill->amount_paid, 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>

                    @if($bill->status !== 'paid')
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Record Payment</h3>
                            <form action="{{ route('admin.bills.update-payment', $bill) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="max-w-md">
                                    <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            ₱
                                        </span>
                                        <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0" max="{{ $bill->total_amount }}"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ old('amount_paid', $bill->amount_paid) }}"
                                            required>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                        Update Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('admin.bills.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Back to Bills
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
