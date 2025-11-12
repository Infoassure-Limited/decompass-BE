<?php

namespace App\Models\VendorDueDiligence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingActionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_meeting_id',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function reviewMeeting()
    {
        return $this->belongsTo(ReviewMeeting::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
