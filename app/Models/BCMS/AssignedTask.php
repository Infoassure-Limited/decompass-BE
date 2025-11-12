<?php

namespace App\Models\BCMS;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AssignedTask extends Model
{
    protected $connection = 'bcms';
    protected $fillable = [
        'client_id',
        'project_id',
        'clause_id',
        'module_activity_task_id',
        'assignee_id',
        'days',
        'start_date',
        'end_date',
        'recurrence',
        'progress',
        'status',
        'assigned_by',
        'notes',
        'recurrence_tag'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function task()
    {
        return $this->belongsTo(ModuleActivityTask::class, 'module_activity_task_id', 'id');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
    public function evidences()
    {
        return $this->hasMany(TaskEvidenceUpload::class, 'assigned_task_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(AssignedTaskComment::class, 'module_activity_task_id', 'id');
    }

    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class, 'assigned_task_id', 'id');
    }

}
