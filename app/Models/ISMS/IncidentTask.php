<?php

namespace App\Models\ISMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IncidentTask extends Model
{

    protected $connection = 'isms';
    protected $fillable = [
        'client_id',
        'incident_id',
        'title',
        'description',
        'assigned_to',
        'status',
        'priority',
        'deadline',
        'evidence_of_task_completion',
        'approval_status',
        'additional_noted'
    ];
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
    public function evidences()
    {
        return $this->hasMany(IncidentEvidence::class, 'task_id', 'id');
    }
}
