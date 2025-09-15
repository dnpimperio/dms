<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['tenant', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.bills.index', compact('bills'));
    }

    public function create()
    {
        $tenants = User::where('role', 'tenant')->get();
        $rooms = Room::all();
        
        return view('admin.bills.create', compact('tenants', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'bill_date' => 'required|date',
            'room_rate' => 'required|numeric|min:0',
            'electricity' => 'required|numeric|min:0',
            'water' => 'required|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
            'other_charges_description' => 'nullable|string',
            'due_date' => 'required|date|after:bill_date',
        ]);

        $total_amount = $request->room_rate + $request->electricity + 
                       $request->water + ($request->other_charges ?? 0);

        $bill = Bill::create([
            'tenant_id' => $request->tenant_id,
            'room_id' => $request->room_id,
            'created_by' => auth()->id(),
            'bill_date' => $request->bill_date,
            'room_rate' => $request->room_rate,
            'electricity' => $request->electricity,
            'water' => $request->water,
            'other_charges' => $request->other_charges ?? 0,
            'other_charges_description' => $request->other_charges_description,
            'total_amount' => $total_amount,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.bills.show', $bill)
            ->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        return view('admin.bills.show', compact('bill'));
    }

    public function generateMonthlyBills()
    {
        $tenants = User::where('role', 'tenant')->get();
        $currentDate = Carbon::now();
        $dueDate = $currentDate->copy()->addDays(15); // Due in 15 days

        foreach ($tenants as $tenant) {
            // Get tenant's current room assignment
            $currentRoom = $tenant->roomAssignments()
                ->where('end_date', null)
                ->first();

            if (!$currentRoom) {
                continue;
            }

            Bill::create([
                'tenant_id' => $tenant->id,
                'room_id' => $currentRoom->room_id,
                'created_by' => auth()->id(),
                'bill_date' => $currentDate->format('Y-m-d'),
                'room_rate' => $currentRoom->room->rate,
                'electricity' => 0, // To be updated manually
                'water' => 0, // To be updated manually
                'total_amount' => $currentRoom->room->rate, // Initial amount, to be updated
                'due_date' => $dueDate->format('Y-m-d'),
            ]);
        }

        return redirect()->route('admin.bills.index')
            ->with('success', 'Monthly bills generated successfully.');
    }

    public function updatePayment(Request $request, Bill $bill)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:'.$bill->total_amount,
        ]);

        $bill->amount_paid = $request->amount_paid;
        $bill->status = $request->amount_paid >= $bill->total_amount ? 'paid' : 
                       ($request->amount_paid > 0 ? 'partially_paid' : 'unpaid');
        $bill->save();

        return redirect()->back()->with('success', 'Payment updated successfully.');
    }
}
