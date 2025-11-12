<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewMeetingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vendor_id' => 'required|exists:vdd.vendors,id',
            'title' => 'required|string|max:255',
            'agenda' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'ended_at' => 'nullable|date|after_or_equal:scheduled_at',
            'duration_minutes' => 'nullable|integer|min:15',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:scheduled,completed,cancelled,postponed',
            'notes' => 'nullable|string',
            'attendees' => 'nullable|array',
            // 'attendees.*.user_id' => 'nullable|exists:users,id',
            // 'attendees.*.name' => 'nullable|string|max:255',
            // 'attendees.*.email' => 'required|email|max:255',
            // 'attendees.*.role' => 'nullable|string|max:255',
            // 'attendees.*.is_external' => 'nullable|boolean',
            // 'attendees.*.confirmed' => 'nullable|boolean',
        ];
    }
}
