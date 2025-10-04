<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tenants = Tenant::latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tenants.create');
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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'gender' => 'required|in:male,female,other',
            'civil_status' => 'required|in:single,married,divorced,widowed,separated',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'personal_email' => 'required|email|unique:tenants,personal_email',
            'permanent_address' => 'required|string',
            'current_address' => 'nullable|string',
            'id_type' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'id_image_path' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        // Create user account for the tenant
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->personal_email,
            'password' => bcrypt('password'), // Default password
            'role' => 'tenant',
            'status' => 'active',
            'gender' => $request->gender,
        ]);

        // Create tenant record
        $tenantData = $request->all();
        $tenantData['user_id'] = $user->id;
        
        Tenant::create($tenantData);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant and user account created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tenant = Tenant::with(['user', 'roomAssignments.room', 'emergencyContacts'])->findOrFail($id);
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'gender' => 'required|in:male,female,other',
            'civil_status' => 'required|in:single,married,divorced,widowed,separated',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'personal_email' => 'required|email|unique:tenants,personal_email,' . $tenant->id,
            'permanent_address' => 'required|string',
            'current_address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
