<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilityRates = \App\Models\UtilityRate::with('utilityType', 'createdBy')
            ->orderBy('effective_from', 'desc')
            ->paginate(10);
        return view('admin.utility-rates.index', compact('utilityRates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $utilityTypes = \App\Models\UtilityType::where('status', 'active')->orderBy('name')->get();
        return view('admin.utility-rates.create', compact('utilityTypes'));
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
            'utility_type_id' => ['required', 'exists:utility_types,id'],
            'rate_per_unit' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        \App\Models\UtilityRate::create([
            'utility_type_id' => $request->utility_type_id,
            'rate_per_unit' => $request->rate_per_unit,
            'effective_date' => $request->effective_date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.utility-rates.index')
            ->with('success', 'Utility rate created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  UtilityRate  $utilityRate
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityRate $utilityRate)
    {
        $utilityRate->load(['utilityType', 'createdBy']);
        
        return view('admin.utility-rates.show', compact('utilityRate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UtilityRate  $utilityRate
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityRate $utilityRate)
    {
        $utilityTypes = UtilityType::where('status', 'active')->orderBy('name')->get();
        
        return view('admin.utility-rates.edit', compact('utilityRate', 'utilityTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  UtilityRate  $utilityRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UtilityRate $utilityRate)
    {
        $request->validate([
            'utility_type_id' => 'required|exists:utility_types,id',
            'rate_per_unit' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $utilityRate->update([
            'utility_type_id' => $request->utility_type_id,
            'rate_per_unit' => $request->rate_per_unit,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
        ]);

        return redirect()->route('admin.utility-rates.index')
            ->with('success', 'Utility rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UtilityRate  $utilityRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(UtilityRate $utilityRate)
    {
        // Check if this rate is referenced by any readings
        if (method_exists($utilityRate, 'readings') && $utilityRate->readings()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete utility rate as it is referenced by utility readings.');
        }

        $utilityRate->delete();

        return redirect()->route('admin.utility-rates.index')
            ->with('success', 'Utility rate deleted successfully.');
    }
}
