<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\MeetingAttendee;
use App\Models\VendorDueDiligence\ReviewMeeting;
use Illuminate\Http\Request;
use App\Http\Resources\MeetingAttendeeResource;
use App\Http\Requests\MeetingAttendeeRequest;

class MeetingAttendeeController extends Controller
{
    public function index(ReviewMeeting $reviewMeeting)
    {
        $attendees = $reviewMeeting->attendees;
        return MeetingAttendeeResource::collection($attendees);
    }

    public function store(ReviewMeeting $reviewMeeting, MeetingAttendeeRequest $request)
    {
        $attendee = $reviewMeeting->attendees()->create($request->validated());
        return new MeetingAttendeeResource($attendee);
    }

    public function show(ReviewMeeting $reviewMeeting, MeetingAttendee $attendee)
    {
        if ($attendee->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Attendee not found for this meeting'], 404);
        }

        return new MeetingAttendeeResource($attendee);
    }

    public function update(ReviewMeeting $reviewMeeting, MeetingAttendee $attendee, MeetingAttendeeRequest $request)
    {
        if ($attendee->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Attendee not found for this meeting'], 404);
        }

        $attendee->update($request->validated());
        return new MeetingAttendeeResource($attendee);
    }

    public function destroy(ReviewMeeting $reviewMeeting, MeetingAttendee $attendee)
    {
        if ($attendee->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Attendee not found for this meeting'], 404);
        }

        $attendee->delete();
        return response()->json(['message' => 'Attendee removed successfully']);
    }

    public function updateConfirmation(ReviewMeeting $reviewMeeting, MeetingAttendee $attendee, Request $request)
    {
        $request->validate([
            'confirmed' => 'required|boolean',
        ]);

        if ($attendee->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Attendee not found for this meeting'], 404);
        }

        $attendee->confirmed = $request->confirmed;
        $attendee->save();

        return new MeetingAttendeeResource($attendee);
    }
}