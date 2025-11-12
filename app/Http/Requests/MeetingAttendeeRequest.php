<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingAttendeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'user_id' => 'nullable|exists:users,id',
            // 'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            // 'role' => 'nullable|string|max:255',
            // 'is_external' => 'nullable|boolean',
            // 'confirmed' => 'nullable|boolean',
        ];
    }
}
