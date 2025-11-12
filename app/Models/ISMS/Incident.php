<?php

namespace App\Models\ISMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $connection = 'isms';
    protected $fillable = [
        'client_id',
        'incident_no',
        'title',
        'description',
        'affected_assets',
        'incident_type_id',
        'reported_by',
        'assigned_to',
        'status',
        'evidence_link',
        'severity',
        'occurred_at',
        'location',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'metadata' => 'array',
    ];
    protected function affectedAssets(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function incidentType()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(IncidentActivityLog::class);
    }

    public function resolutionActions()
    {
        return $this->hasMany(ImmediateResolutionAction::class);
    }

    public function rootCauseAnalyses()
    {
        return $this->hasMany(IncidentRootCauseAnalysis::class);
    }
    public function tasks()
    {
        return $this->hasMany(IncidentTask::class);
    }
}
