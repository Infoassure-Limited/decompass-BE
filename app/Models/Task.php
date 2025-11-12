<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'assigned_to',
        'assigned_by',
        'title',
        'description',
        'module',
        'priority',
        'start_date',
        'end_date',
        'completed_at',
        'comments',
        'status',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
}