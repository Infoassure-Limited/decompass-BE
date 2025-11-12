<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCSARiskAssessment extends Model
{
    use HasFactory;
    protected $table = 'rcsa_risk_assessments';
    protected $fillable = ['module', 'risk_register_id', 'client_id', 'business_unit_id', 'business_process_id', 'standard_id', 'asset_type_id', 'asset', 'impact_data', 'likelihood_rationale', 'likelihood_of_occurence', 'impact_of_occurence', 'overall_risk_rating', 'risk_category', 'assessment_option', 'recommended_control', 'applicable_annexture', 'risk_treatment_option_b', 'risk_treatment_plan_residual', 'control_effectiveness_level', 'revised_impact_data', 'revised_likelihood_rationale', 'revised_likelihood_of_occurence', 'revised_impact_of_occurence', 'revised_overall_risk_rating', 'revised_risk_category', 'key_risk_indicator', 'status', 'comments'];

    protected function impactData(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function revisedImpactData(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    protected function treatmentOptionDetails(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function keyRiskIndicatorAssessment()
    {
        return $this->hasOne(KeyRiskIndicatorAssessment::class);
    }
}
