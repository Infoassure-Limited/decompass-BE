<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorDueDiligence\ReviewMeeting;
use App\Models\VendorDueDiligence\Vendor;
use App\Http\Resources\ReviewMeetingResource;
use App\Http\Requests\ReviewMeetingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewMeetingController extends Controller
{
    public function index(Request $request)
    {
        $query = ReviewMeeting::with(['vendor', 'attendees']);

        // Filter by vendor if provided
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('scheduled_at', [$request->start_date, $request->end_date]);
        }

        $meetings = $query->orderBy('scheduled_at', 'desc')->get();
        return ReviewMeetingResource::collection($meetings);
    }

    public function store(ReviewMeetingRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create the meeting
            $meeting = new ReviewMeeting($request->validated());
            $meeting->scheduled_by = $request->scheduled_by;
            $meeting->save();

            // Add attendees if provided
            if ($request->has('attendees') && is_array($request->attendees)) {
                foreach ($request->attendees as $attendee) {
                    $meeting->attendees()->create(['email' => $attendee]);
                }
            }

            DB::commit();
            return new ReviewMeetingResource($meeting->load(['vendor', 'attendees']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create meeting', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(ReviewMeeting $reviewMeeting)
    {
        return new ReviewMeetingResource($reviewMeeting->load(['vendor', 'attendees', 'actionItems']));
    }

    public function update(ReviewMeetingRequest $request, ReviewMeeting $reviewMeeting)
    {
        try {
            DB::beginTransaction();

            $reviewMeeting->update($request->validated());

            // Update attendees if provided
            if ($request->has('attendees') && is_array($request->attendees)) {
                // Remove existing attendees
                $reviewMeeting->attendees()->delete();

                // Add new attendees
                foreach ($request->attendees as $attendee) {
                    $reviewMeeting->attendees()->create($attendee);
                }
            }

            DB::commit();
            return new ReviewMeetingResource($reviewMeeting->load(['vendor', 'attendees']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update meeting', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(ReviewMeeting $reviewMeeting)
    {
        $reviewMeeting->delete();
        return response()->json(['message' => 'Meeting deleted successfully']);
    }

    public function updateStatus(Request $request, ReviewMeeting $reviewMeeting)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,postponed',
        ]);

        $reviewMeeting->status = $request->status;

        if ($request->status === 'completed' && !$reviewMeeting->ended_at) {
            $reviewMeeting->ended_at = now();
        }

        $reviewMeeting->save();

        return new ReviewMeetingResource($reviewMeeting);
    }

    public function getVendorMeetings(Vendor $vendor)
    {
        $meetings = $vendor->reviewMeetings()->with(['attendees'])->orderBy('scheduled_at', 'desc')->paginate(15);
        return ReviewMeetingResource::collection($meetings);
    }
}