<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskControlSelfAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'business_unit_id', 'business_process_id', 'rcm_id', 'category', 'key_process', 'control_activities', 'control_owner', 'source', 'control_type', 'risk_description', 'risk_rating', 'self_assessment_control', 'self_assessment_score', 'comment_on_status', 'rm_rating_of_control', 'validation', 'basis_of_rm_rating', 'self_assessment_of_process_level_risk', 'rm_validated_process_level_risk', 'overall_process_control_rating'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class);
    }
}
