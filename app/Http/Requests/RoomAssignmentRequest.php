<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'admin';
    }

    public function rules()
    {
        $rules = [
            'room_id' => ['required', 'exists:rooms,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'active', 'completed'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['start_date'] = ['required', 'date'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'room_id.required' => 'Please select a room.',
            'tenant_id.required' => 'Please select a tenant.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.after' => 'End date must be after the start date.',
            'monthly_rent.min' => 'Monthly rent must be greater than 0.',
        ];
    }
}
