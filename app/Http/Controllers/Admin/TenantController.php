<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TenantController extends Controller
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
    public function index()
    {
        $tenants = Tenant::with(['user', 'emergencyContacts'])->latest()->paginate(10);
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
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'nationality' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'personal_email' => ['required', 'string', 'email', 'max:255', 'unique:tenants'],
            'permanent_address' => ['required', 'string'],
            'current_address' => ['nullable', 'string'],
            'id_type' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:255'],
            'id_image' => ['required', 'image', 'max:2048'],
            'remarks' => ['nullable', 'string'],
            
            // User account information
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],

            // Emergency contacts
            'emergency_contacts' => ['required', 'array', 'min:1'],
            'emergency_contacts.*.name' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.relationship' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.phone_number' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.alternative_phone' => ['nullable', 'string', 'max:255'],
            'emergency_contacts.*.email' => ['nullable', 'string', 'email', 'max:255'],
            'emergency_contacts.*.address' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tenant',
                'status' => 'active'
            ]);

            // Store ID image
            $idImagePath = $request->file('id_image')->store('tenant-ids', 'public');

            // Create tenant profile
            $tenant = Tenant::create(array_merge(
                $request->except(['email', 'password', 'emergency_contacts', 'id_image']),
                [
                    'user_id' => $user->id,
                    'id_image_path' => $idImagePath
                ]
            ));

            // Create emergency contacts
            $tenant->emergencyContacts()->createMany($request->emergency_contacts);

            DB::commit();
            return redirect()->route('admin.tenants.index')
                ->with('success', 'Tenant profile created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($idImagePath ?? '');
            return back()->with('error', 'Error creating tenant profile. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['user', 'emergencyContacts']);
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenant)
    {
        $tenant->load(['user', 'emergencyContacts']);
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'nationality' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'personal_email' => ['required', 'string', 'email', 'max:255', Rule::unique('tenants')->ignore($tenant->id)],
            'permanent_address' => ['required', 'string'],
            'current_address' => ['nullable', 'string'],
            'id_type' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:255'],
            'id_image' => ['nullable', 'image', 'max:2048'],
            'remarks' => ['nullable', 'string'],
            
            // User account information
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($tenant->user_id)],
            'password' => ['nullable', 'string', 'min:8'],

            // Emergency contacts
            'emergency_contacts' => ['required', 'array', 'min:1'],
            'emergency_contacts.*.name' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.relationship' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.phone_number' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.alternative_phone' => ['nullable', 'string', 'max:255'],
            'emergency_contacts.*.email' => ['nullable', 'string', 'email', 'max:255'],
            'emergency_contacts.*.address' => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $tenant->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email
            ]);

            if ($request->filled('password')) {
                $tenant->user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            // Update ID image if provided
            if ($request->hasFile('id_image')) {
                Storage::disk('public')->delete($tenant->id_image_path);
                $idImagePath = $request->file('id_image')->store('tenant-ids', 'public');
                $tenant->id_image_path = $idImagePath;
            }

            // Update tenant profile
            $tenant->update($request->except(['email', 'password', 'emergency_contacts', 'id_image']));

            // Update emergency contacts
            $tenant->emergencyContacts()->delete();
            $tenant->emergencyContacts()->createMany($request->emergency_contacts);

            DB::commit();
            return redirect()->route('admin.tenants.index')
                ->with('success', 'Tenant profile updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($idImagePath)) {
                Storage::disk('public')->delete($idImagePath);
            }
            return back()->with('error', 'Error updating tenant profile. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant)
    {
        try {
            DB::beginTransaction();

            // Delete ID image
            Storage::disk('public')->delete($tenant->id_image_path);

            // Delete tenant (this will cascade to emergency contacts due to foreign key constraint)
            $tenant->delete();

            // Delete user account
            $tenant->user->delete();

            DB::commit();
            return redirect()->route('admin.tenants.index')
                ->with('success', 'Tenant profile deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting tenant profile. ' . $e->getMessage());
        }
    }
}
