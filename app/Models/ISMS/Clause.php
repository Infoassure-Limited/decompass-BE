<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class Clause extends Model
{
    //
    protected $connection = 'isms';
    protected $fillable = ['sort_by', 'name', 'description'];
    public function responseMonitors()
    {
        return $this->hasMany(ComplianceResponseMonitor::class, 'clause_id', 'id');
    }
    public function questions()
    {
        return $this->hasMany(ComplianceQuestion::class, 'clause_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(ModuleActivity::class, 'clause_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(ModuleActivityTask::class, 'clause_id', 'id');
    }
}
