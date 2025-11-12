<?php

namespace App\Models\ISMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AssignedTaskComment extends Model
{
    protected $connection = 'isms';
    protected $fillable = ['client_id', 'assigned_task_id', 'comment', 'comment_by', 'is_deleted'];

    public function commenter()
    {
        return $this->belongsTo(User::class, 'comment_by', 'id');
    }
    public function assignedTask()
    {
        return $this->belongsTo(AssignedTask::class);
    }
}
