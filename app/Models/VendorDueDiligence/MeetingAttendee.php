<?php

namespace App\Models\VendorDueDiligence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendee extends Model
{
    use HasFactory;
    protected $connection = 'vdd';
    protected $fillable = [
        'review_meeting_id',
        'user_id',
        'name',
        'email',
        'role',
        'is_external',
        'confirmed'
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'confirmed' => 'boolean',
    ];

    public function reviewMeeting()
    {
        return $this->belongsTo(ReviewMeeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
