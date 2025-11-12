<?php

namespace App\Models\ISMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ImmediateResolutionAction extends Model
{
    //
    protected $connection = 'isms';

    protected $fillable = [
        'client_id',
        'incident_id',
        'immediate_action_taken',
        'is_escalated',
        'assigned_to',
        'escalation_details',
        'deadline',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
