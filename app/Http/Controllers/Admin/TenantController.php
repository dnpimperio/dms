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
            'first_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'middle_name' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'last_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'gender' => ['required', Rule::in(['female'])], // Female dormitory only
            'nationality' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'course' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10,11}$/', 'unique:tenants,phone_number'],
            'alternative_phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'personal_email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:tenants,personal_email'],
            'provincial_address' => ['required', 'string', 'max:500'],
            'current_address' => ['nullable', 'string', 'max:500'],
            'id_type' => ['required', Rule::in(['drivers_license', 'passport', 'national_id', 'voters_id', 'tin_id', 'sss_id', 'philhealth_id', 'pag_ibig_id', 'postal_id', 'barangay_id', 'senior_citizen_id', 'pwd_id'])],
            'id_number' => ['required', 'string', 'max:255', 'unique:tenants,id_number'],
            'id_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            
            // User account information
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Emergency contacts
            'emergency_contacts' => ['required', 'array', 'min:1', 'max:3'],
            'emergency_contacts.*.name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'emergency_contacts.*.relationship' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.phone_number' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'emergency_contacts.*.alternative_phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'emergency_contacts.*.email' => ['nullable', 'string', 'email:rfc,dns', 'max:255'],
            'emergency_contacts.*.address' => ['required', 'string', 'max:500'],
        ], [
            'birth_date.before_or_equal' => 'Tenant must be at least 18 years old.',
            'first_name.regex' => 'First name must only contain letters and spaces.',
            'middle_name.regex' => 'Middle name must only contain letters and spaces.',
            'last_name.regex' => 'Last name must only contain letters and spaces.',
            'emergency_contacts.*.name.regex' => 'Emergency contact name must only contain letters and spaces.',
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

            // Store ID image with better error handling
            $idImagePath = null;
            if ($request->hasFile('id_image')) {
                $file = $request->file('id_image');
                if ($file->isValid()) {
                    // Create filename with timestamp to avoid conflicts
                    $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                    $idImagePath = $file->storeAs('tenant-ids', $filename, 'public');
                    
                    if (!$idImagePath) {
                        throw new \Exception('Failed to store ID image');
                    }
                } else {
                    throw new \Exception('Invalid ID image file');
                }
            } else {
                throw new \Exception('No ID image file received');
            }

            // Create tenant profile
            $tenant = Tenant::create(array_merge(
                $request->except(['email', 'password', 'emergency_contacts', 'id_image']),
                [
                    'user_id' => $user->id,
                    'id_image_path' => $idImagePath,
                    'gender' => 'female' // Force gender to female for female dormitory
                ]
            ));

            // Create emergency contacts
            if ($request->has('emergency_contacts') && is_array($request->emergency_contacts)) {
                $tenant->emergencyContacts()->createMany($request->emergency_contacts);
            }

            DB::commit();
            return redirect()->route('admin.tenants.index')
                ->with('success', 'Tenant profile created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Clean up uploaded file if it exists
            if ($idImagePath && Storage::disk('public')->exists($idImagePath)) {
                Storage::disk('public')->delete($idImagePath);
            }
            return back()->with('error', 'Error creating tenant profile: ' . $e->getMessage())
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
            'first_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'middle_name' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'last_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'gender' => ['required', Rule::in(['female'])], // Female dormitory only
            'nationality' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'course' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10,11}$/', Rule::unique('tenants', 'phone_number')->ignore($tenant->id)],
            'alternative_phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'personal_email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('tenants', 'personal_email')->ignore($tenant->id)],
            'provincial_address' => ['required', 'string', 'max:500'],
            'current_address' => ['nullable', 'string', 'max:500'],
            'id_type' => ['required', Rule::in(['drivers_license', 'passport', 'national_id', 'voters_id', 'tin_id', 'sss_id', 'philhealth_id', 'pag_ibig_id', 'postal_id', 'barangay_id', 'senior_citizen_id', 'pwd_id'])],
            'id_number' => ['required', 'string', 'max:255', Rule::unique('tenants', 'id_number')->ignore($tenant->id)],
            'id_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            
            // User account information
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($tenant->user_id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // Emergency contacts
            'emergency_contacts' => ['required', 'array', 'min:1', 'max:3'],
            'emergency_contacts.*.name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'emergency_contacts.*.relationship' => ['required', 'string', 'max:255'],
            'emergency_contacts.*.phone_number' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'emergency_contacts.*.alternative_phone' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'emergency_contacts.*.email' => ['nullable', 'string', 'email:rfc,dns', 'max:255'],
            'emergency_contacts.*.address' => ['required', 'string', 'max:500'],
        ], [
            'birth_date.before_or_equal' => 'Tenant must be at least 18 years old.',
            'first_name.regex' => 'First name must only contain letters and spaces.',
            'middle_name.regex' => 'Middle name must only contain letters and spaces.',
            'last_name.regex' => 'Last name must only contain letters and spaces.',
            'emergency_contacts.*.name.regex' => 'Emergency contact name must only contain letters and spaces.',
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
