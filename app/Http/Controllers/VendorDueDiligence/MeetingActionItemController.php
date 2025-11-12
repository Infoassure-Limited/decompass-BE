<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\MeetingActionItem;
use App\Models\VendorDueDiligence\ReviewMeeting;
use Illuminate\Http\Request;
use App\Http\Resources\MeetingActionItemResource;
use App\Http\Requests\MeetingActionItemRequest;

class MeetingActionItemController extends Controller
{
    public function index(ReviewMeeting $reviewMeeting)
    {
        $actionItems = $reviewMeeting->actionItems;
        return MeetingActionItemResource::collection($actionItems);
    }

    public function store(ReviewMeeting $reviewMeeting, MeetingActionItemRequest $request)
    {
        $actionItem = $reviewMeeting->actionItems()->create($request->validated());
        return new MeetingActionItemResource($actionItem);
    }

    public function show(ReviewMeeting $reviewMeeting, MeetingActionItem $actionItem)
    {
        if ($actionItem->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Action item not found for this meeting'], 404);
        }

        return new MeetingActionItemResource($actionItem);
    }

    public function update(ReviewMeeting $reviewMeeting, MeetingActionItem $actionItem, MeetingActionItemRequest $request)
    {
        if ($actionItem->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Action item not found for this meeting'], 404);
        }

        $actionItem->update($request->validated());
        return new MeetingActionItemResource($actionItem);
    }

    public function destroy(ReviewMeeting $reviewMeeting, MeetingActionItem $actionItem)
    {
        if ($actionItem->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Action item not found for this meeting'], 404);
        }

        $actionItem->delete();
        return response()->json(['message' => 'Action item deleted successfully']);
    }

    public function updateStatus(ReviewMeeting $reviewMeeting, MeetingActionItem $actionItem, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        if ($actionItem->review_meeting_id !== $reviewMeeting->id) {
            return response()->json(['message' => 'Action item not found for this meeting'], 404);
        }

        $actionItem->status = $request->status;
        $actionItem->save();

        return new MeetingActionItemResource($actionItem);
    }
}