<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class ComplianceResponseMonitor extends Model
{
    //
    protected $connection = 'isms';
    protected $fillable = ['client_id', 'project_id', 'clause_id', 'is_submitted', 'date_submitted', 'submitted_by'];

    public function responses()
    {
        return $this->hasMany(ComplianceResponse::class, 'compliance_response_monitor_id', 'id');
    }
}
