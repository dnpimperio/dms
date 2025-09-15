<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Room::orderBy('room_number');
        
        // Show hidden rooms if requested
        if (!$request->show_hidden) {
            $query->where('hidden', false);
        }
        
        $rooms = $query->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleHidden(Room $room)
    {
        $room->update(['hidden' => !$room->hidden]);
        return back()->with('success', 'Room visibility updated successfully');
    }

    public function toggleStatus(Room $room)
    {
        $newStatus = $room->status === 'available' ? 'unavailable' : 'available';
        $room->update(['status' => $newStatus]);
        return back()->with('success', 'Room status updated successfully');
    }

    public function updateRate(Request $request, Room $room)
    {
        $request->validate([
            'rate' => ['required', 'numeric', 'min:0']
        ]);

        $room->update(['rate' => $request->rate]);
        return back()->with('success', 'Room rate updated successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => ['required', 'string', 'max:255', 'unique:rooms'],
            'type' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:2'],
            'status' => ['required', Rule::in(['available', 'reserved', 'occupied', 'maintenance'])],
            'description' => ['nullable', 'string']
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        $room->load(['currentAssignments.tenant']);
        $room->current_occupants = $room->getCurrentOccupantsAttribute();
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => ['required', 'string', 'max:255', Rule::unique('rooms')->ignore($room->id)],
            'type' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:2'],
            'status' => ['required', Rule::in(['available', 'reserved', 'occupied', 'maintenance'])],
            'description' => ['nullable', 'string']
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        if ($room->status === 'occupied') {
            return back()->with('error', 'Cannot delete an occupied room.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    /**
     * Update room status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => ['required', Rule::in(['available', 'reserved', 'occupied', 'maintenance'])]
        ]);

        $room->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.rooms.show', $room)
            ->with('success', 'Room status updated successfully.');
    }
}
