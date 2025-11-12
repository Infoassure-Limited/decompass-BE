<?php

namespace App\Models\Policy;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyVersion extends Model
{
    use HasFactory;
    protected $connection = 'sec_policies';
    protected $fillable = [
        'policy_id',
        'version_number',
        'content',
        'change_summary',
        'created_by',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
