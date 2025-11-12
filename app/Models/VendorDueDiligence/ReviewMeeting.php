<?php

namespace App\Models\VendorDueDiligence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewMeeting extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'vdd';
    protected $fillable = [
        'vendor_id',
        'title',
        'agenda',
        'scheduled_at',
        'ended_at',
        'duration_minutes',
        'location',
        'meeting_link',
        'status',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }

    public function actionItems()
    {
        return $this->hasMany(MeetingActionItem::class);
    }
}
