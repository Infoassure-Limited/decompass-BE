<?php

namespace App\Models\ISMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class IncidentActivityLog extends Model
{
    protected $connection = 'isms';
    protected $fillable = [
        'client_id',
        'incident_id',
        'user_id',
        'action',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}