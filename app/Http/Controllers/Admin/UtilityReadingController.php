<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UtilityReading;
use App\Models\UtilityType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilityReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = UtilityReading::with(['utilityType', 'room', 'recordedBy']);

        // Filter by utility type
        if ($request->has('utility_type') && $request->utility_type != '') {
            $query->where('utility_type_id', $request->utility_type);
        }

        // Filter by room
        if ($request->has('room') && $request->room != '') {
            $query->where('room_id', $request->room);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('reading_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('reading_date', '<=', $request->date_to);
        }

        $utilityReadings = $query->orderBy('reading_date', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        $utilityTypes = UtilityType::where('status', 'active')->orderBy('name')->get();
        $rooms = Room::orderBy('room_number')->get();

        return view('admin.utility-readings.index', compact('utilityReadings', 'utilityTypes', 'rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $utilityTypes = UtilityType::where('status', 'active')->orderBy('name')->get();
        $rooms = Room::orderBy('room_number')->get();

        // Pre-select utility type and room if passed via query parameters
        $selectedUtilityType = $request->get('utility_type');
        $selectedRoom = $request->get('room');

        return view('admin.utility-readings.create', compact('utilityTypes', 'rooms', 'selectedUtilityType', 'selectedRoom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'utility_type_id' => 'required|exists:utility_types,id',
            'room_id' => 'required|exists:rooms,id',
            'reading_date' => 'required|date|before_or_equal:today',
            'current_reading' => 'required|numeric|min:0',
            'previous_reading' => 'nullable|numeric|min:0|lt:current_reading',
            'notes' => 'nullable|string|max:500',
        ], [
            'previous_reading.lt' => 'Previous reading must be less than current reading.',
        ]);

        // Check for duplicate readings
        $existingReading = UtilityReading::where('utility_type_id', $request->utility_type_id)
                                       ->where('room_id', $request->room_id)
                                       ->whereDate('reading_date', $request->reading_date)
                                       ->first();

        if ($existingReading) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['reading_date' => 'A reading for this utility type and room already exists for the selected date.']);
        }

        // Get previous reading if not provided
        $previousReading = $request->previous_reading;
        if (!$previousReading) {
            $lastReading = UtilityReading::where('utility_type_id', $request->utility_type_id)
                                       ->where('room_id', $request->room_id)
                                       ->where('reading_date', '<', $request->reading_date)
                                       ->orderBy('reading_date', 'desc')
                                       ->first();
            $previousReading = $lastReading ? $lastReading->current_reading : 0;
        }

        // Calculate consumption
        $consumption = $request->current_reading - $previousReading;

        UtilityReading::create([
            'utility_type_id' => $request->utility_type_id,
            'room_id' => $request->room_id,
            'reading_date' => $request->reading_date,
            'current_reading' => $request->current_reading,
            'previous_reading' => $previousReading,
            'consumption' => $consumption,
            'notes' => $request->notes,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()->route('admin.utility-readings.index')
            ->with('success', 'Utility reading recorded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  UtilityReading  $utilityReading
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityReading $utilityReading)
    {
        $utilityReading->load(['utilityType', 'room', 'recordedBy']);

        return view('admin.utility-readings.show', compact('utilityReading'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UtilityReading  $utilityReading
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityReading $utilityReading)
    {
        $utilityTypes = UtilityType::where('status', 'active')->orderBy('name')->get();
        $rooms = Room::orderBy('room_number')->get();

        return view('admin.utility-readings.edit', compact('utilityReading', 'utilityTypes', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  UtilityReading  $utilityReading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UtilityReading $utilityReading)
    {
        $request->validate([
            'utility_type_id' => 'required|exists:utility_types,id',
            'room_id' => 'required|exists:rooms,id',
            'reading_date' => 'required|date|before_or_equal:today',
            'current_reading' => 'required|numeric|min:0',
            'previous_reading' => 'nullable|numeric|min:0|lt:current_reading',
            'notes' => 'nullable|string|max:500',
        ], [
            'previous_reading.lt' => 'Previous reading must be less than current reading.',
        ]);

        // Check for duplicate readings (excluding current record)
        $existingReading = UtilityReading::where('utility_type_id', $request->utility_type_id)
                                       ->where('room_id', $request->room_id)
                                       ->whereDate('reading_date', $request->reading_date)
                                       ->where('id', '!=', $utilityReading->id)
                                       ->first();

        if ($existingReading) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['reading_date' => 'A reading for this utility type and room already exists for the selected date.']);
        }

        // Get previous reading if not provided
        $previousReading = $request->previous_reading ?? $utilityReading->previous_reading;

        // Calculate consumption
        $consumption = $request->current_reading - $previousReading;

        $utilityReading->update([
            'utility_type_id' => $request->utility_type_id,
            'room_id' => $request->room_id,
            'reading_date' => $request->reading_date,
            'current_reading' => $request->current_reading,
            'previous_reading' => $previousReading,
            'consumption' => $consumption,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.utility-readings.index')
            ->with('success', 'Utility reading updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UtilityReading  $utilityReading
     * @return \Illuminate\Http\Response
     */
    public function destroy(UtilityReading $utilityReading)
    {
        $utilityReading->delete();

        return redirect()->route('admin.utility-readings.index')
            ->with('success', 'Utility reading deleted successfully.');
    }

    /**
     * Get previous reading for AJAX requests
     */
    public function getPreviousReading(Request $request)
    {
        $request->validate([
            'utility_type_id' => 'required|exists:utility_types,id',
            'room_id' => 'required|exists:rooms,id',
            'reading_date' => 'required|date',
        ]);

        $lastReading = UtilityReading::where('utility_type_id', $request->utility_type_id)
                                   ->where('room_id', $request->room_id)
                                   ->where('reading_date', '<', $request->reading_date)
                                   ->orderBy('reading_date', 'desc')
                                   ->first();

        return response()->json([
            'previous_reading' => $lastReading ? $lastReading->current_reading : 0,
            'last_reading_date' => $lastReading ? $lastReading->reading_date->format('Y-m-d') : null,
        ]);
    }
}
