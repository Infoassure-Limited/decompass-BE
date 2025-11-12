<?php

namespace App\Models\Policy;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyAudit extends Model
{
    use HasFactory;
    protected $connection = 'sec_policies';

    protected $fillable = [
        'client_id',
        'policy_id',
        'user_id',
        'action',
        'details',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}