<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilityTypes = \App\Models\UtilityType::orderBy('name')->paginate(10);
        return view('admin.utility-types.index', compact('utilityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.utility-types.create');
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
            'name' => ['required', 'string', 'max:255', 'unique:utility_types,name'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive']
        ]);

        \App\Models\UtilityType::create($request->all());

        return redirect()->route('admin.utility-types.index')
            ->with('success', 'Utility type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Models\UtilityType $utilityType)
    {
        $utilityType->load(['rates' => function($query) {
            $query->orderBy('effective_date', 'desc');
        }]);
        
        return view('admin.utility-types.show', compact('utilityType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Models\UtilityType $utilityType)
    {
        return view('admin.utility-types.edit', compact('utilityType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\Models\UtilityType $utilityType)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:utility_types,name,' . $utilityType->id],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive']
        ]);

        $utilityType->update($request->all());

        return redirect()->route('admin.utility-types.index')
            ->with('success', 'Utility type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Models\UtilityType $utilityType)
    {
        // Check if utility type has any readings or bills
        if ($utilityType->readings()->exists() || $utilityType->rates()->exists()) {
            return back()->with('error', 'Cannot delete utility type that has readings or rates associated with it.');
        }

        $utilityType->delete();

        return redirect()->route('admin.utility-types.index')
            ->with('success', 'Utility type deleted successfully.');
    }
}
