<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class KeyRiskIndicatorAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'business_unit_id', 'risk_assessment_id', 'frequency_of_assessment', 'unit_of_measurement', 'risk_trigger_threshold', 'assessments', 'comment'];

    protected function riskTriggerThreshold(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function assessments(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }
}
