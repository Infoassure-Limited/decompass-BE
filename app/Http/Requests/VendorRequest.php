<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ];

        // For update, make the email unique except for the current vendor
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules['email'] = 'required|email|max:255|unique:vendors,email,' . $this->route('vendor')->id;
        } else {
            $rules['email'] = 'required|email|max:255|unique:vendors,email';
        }

        return $rules;
    }
}
