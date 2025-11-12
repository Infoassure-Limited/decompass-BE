<?php

namespace App\Models\BCMS;


use App\Models\BusinessProcess;
use App\Models\BusinessUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessImpactAnalysis extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'bcms';
    protected $fillable = ['client_id', 'business_unit_id', 'business_process_id', 'minimum_service_level'];
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class);
    }
    public function impacts()
    {
        return $this->hasMany(ProcessDisruptionImpact::class, 'business_impact_analysis_id', 'id');
    }

}
