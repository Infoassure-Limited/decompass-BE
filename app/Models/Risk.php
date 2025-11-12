<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['client_id', 'business_unit_id', 'business_process_id', 'risk_unique_id', 'type', 'description', 'outcome', 'risk_owner', 'control_no', 'control_location', 'control_description', 'control_frequency', 'control_owner', 'control_type', 'nature_of_control', 'application_used_for_control', 'compensating_control', 'test_procedures', 'sample_size', 'data_required', 'link_to_evidence', 'test_conclusion', 'gap_description', 'tod_improvement_opportunity', 'recommendation', 'responsibility', 'timeline', 'tod_gap_status'];
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
