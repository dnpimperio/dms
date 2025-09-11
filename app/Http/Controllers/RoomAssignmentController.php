<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\RoomAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoomAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomAssignment::with(['room', 'tenant'])
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->search, function($q) use ($request) {
                return $q->whereHas('room', function($q) use ($request) {
                    $q->where('room_number', 'like', "%{$request->search}%");
                })->orWhereHas('tenant', function($q) use ($request) {
                    $q->where('full_name', 'like', "%{$request->search}%");
                });
            });

        $assignments = $query->latest()->paginate(10)->withQueryString();
        $statuses = ['pending', 'active', 'completed'];

        return view('room-assignments.index', compact('assignments', 'statuses'));
    }

    public function create(Request $request)
    {
        // Get available rooms (either unoccupied or not in active assignment)
        $rooms = Room::where(function($query) {
            $query->where('status', '!=', 'maintenance')
                ->whereDoesntHave('assignments', function($q) {
                    $q->where('status', 'active');
                })
                ->orWhere('status', 'available');
        });

        // If room_id is provided, ensure that room is included regardless of status
        if ($request->has('room_id')) {
            $rooms = $rooms->orWhere('id', $request->room_id);
        }

        $rooms = $rooms->get();

        // Get tenants who don't have active assignments
        $tenants = Tenant::whereDoesntHave('roomAssignments', function($query) {
            $query->where('status', 'active');
        })->get();
        
        // Get the selected room for pre-filling if provided
        $selectedRoom = $request->has('room_id') ? Room::find($request->room_id) : null;
        
        return view('room-assignments.create', compact('rooms', 'tenants', 'selectedRoom'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Check if room is available
            $isRoomAvailable = !RoomAssignment::where('room_id', $validated['room_id'])
                ->where('status', 'active')
                ->exists();

            if (!$isRoomAvailable) {
                return back()->withErrors(['room_id' => 'Room is currently occupied.'])->withInput();
            }

            // Check if tenant already has an active assignment
            $hasTenantAssignment = RoomAssignment::where('tenant_id', $validated['tenant_id'])
                ->where('status', 'active')
                ->exists();

            if ($hasTenantAssignment) {
                return back()->withErrors(['tenant_id' => 'Tenant already has an active room assignment.'])->withInput();
            }

            // Create assignment with initial active status
            $assignment = RoomAssignment::create(array_merge($validated, [
                'status' => 'active'
            ]));

            // Update room status and increment occupants
            $room = Room::find($validated['room_id']);
            $room->update([
                'status' => 'occupied',
                'current_occupants' => $room->getCurrentOccupantsAttribute()
            ]);

            DB::commit();

            return redirect()
                ->route('room-assignments.index')
                ->with('success', 'Room assignment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to create room assignment. Please try again.'])
                ->withInput();
        }
    }

    public function show(RoomAssignment $roomAssignment)
    {
        $roomAssignment->load(['room', 'tenant']);
        return view('room-assignments.show', compact('roomAssignment'));
    }

    public function edit(RoomAssignment $roomAssignment)
    {
        // Get all rooms that are either available or currently assigned to this assignment
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $roomAssignment->room_id)
            ->get();

        // Get all tenants who don't have active assignments or are part of this assignment
        $tenants = Tenant::whereDoesntHave('roomAssignments', function($query) use ($roomAssignment) {
            $query->where('status', 'active')
                  ->where('id', '!=', $roomAssignment->id);
        })
        ->orWhere('id', $roomAssignment->tenant_id)
        ->get();

        return view('room-assignments.edit', compact('roomAssignment', 'rooms', 'tenants'));
    }

    public function update(Request $request, RoomAssignment $roomAssignment)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'status' => 'required|in:active,pending,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // If changing room, check if new room is available
            if ($roomAssignment->room_id !== $validated['room_id']) {
                $isNewRoomAvailable = !RoomAssignment::where('room_id', $validated['room_id'])
                    ->where('status', 'active')
                    ->where('id', '!=', $roomAssignment->id)
                    ->exists();

                if (!$isNewRoomAvailable) {
                    return back()->withErrors(['room_id' => 'Selected room is currently occupied.'])->withInput();
                }
            }

            // If changing tenant, check if new tenant has active assignment
            if ($roomAssignment->tenant_id !== $validated['tenant_id']) {
                $hasNewTenantAssignment = RoomAssignment::where('tenant_id', $validated['tenant_id'])
                    ->where('status', 'active')
                    ->where('id', '!=', $roomAssignment->id)
                    ->exists();

                if ($hasNewTenantAssignment) {
                    return back()->withErrors(['tenant_id' => 'Selected tenant already has an active assignment.'])->withInput();
                }
            }

            // Handle room status changes
            if ($validated['status'] === 'completed' || $validated['status'] === 'pending') {
                // Make current room available
                Room::where('id', $roomAssignment->room_id)->update(['status' => 'available']);
            } elseif ($validated['status'] === 'active') {
                // If assignment becomes active
                if ($roomAssignment->room_id !== $validated['room_id']) {
                    // If room is changing, update both old and new room status
                    Room::where('id', $roomAssignment->room_id)->update(['status' => 'available']);
                    Room::where('id', $validated['room_id'])->update(['status' => 'occupied']);
                } else {
                    // Just make sure current room is marked as occupied
                    Room::where('id', $roomAssignment->room_id)->update(['status' => 'occupied']);
                }
            }

            $roomAssignment->update($validated);

            DB::commit();

            return redirect()
                ->route('room-assignments.index')
                ->with('success', 'Room assignment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to update room assignment. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(RoomAssignment $roomAssignment)
    {
        try {
            DB::beginTransaction();

            // Only make room available if the assignment was active
            if ($roomAssignment->status === 'active') {
                $room = Room::find($roomAssignment->room_id);
                $remainingAssignments = $room->currentAssignments()->count();
                
                // If this was the last active assignment, make room available
                if ($remainingAssignments <= 1) {
                    $room->update([
                        'status' => 'available',
                        'current_occupants' => 0
                    ]);
                }
            }
            
            $roomAssignment->delete();

            DB::commit();
            return redirect()
                ->route('room-assignments.index')
                ->with('success', 'Room assignment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete room assignment.']);
        }
    }

    public function end(RoomAssignment $roomAssignment)
    {
        try {
            DB::beginTransaction();

            if ($roomAssignment->status !== 'active') {
                return back()->withErrors(['error' => 'Only active assignments can be ended.']);
            }

            $roomAssignment->update([
                'status' => 'completed',
                'end_date' => now()
            ]);

            // Update room status and occupants
            $room = Room::find($roomAssignment->room_id);
            $remainingActiveAssignments = $room->currentAssignments()->count();

            if ($remainingActiveAssignments === 0) {
                $room->update([
                    'status' => 'available',
                    'current_occupants' => 0
                ]);
            } else {
                $room->update([
                    'current_occupants' => $remainingActiveAssignments
                ]);
            }

            DB::commit();
            return back()->with('success', 'Room assignment ended successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to end room assignment.']);
        }
    }
}
