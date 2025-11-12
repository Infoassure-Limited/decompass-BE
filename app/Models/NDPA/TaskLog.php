<?php

namespace App\Models\NDPA;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $connection = 'ndpa';
    protected $fillable = [
        'client_id',
        'assigned_task_id',
        'triggered_by',
        'executed_at',
        'start_date',
        'deadline',
        'notes',
        'recurrence_tag',
        'status'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function task()
    {
        return $this->belongsTo(AssignedTask::class, 'assigned_task_id', 'id');
    }
}
