<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RiskAssessment extends Model
{
    use HasFactory;
    // protected $fillable = ['module', 'risk_register_id', 'client_id', 'business_unit_id', 'business_process_id', 'standard_id', 'asset_type_id', 'asset', 'impact_data', 'likelihood_rationale', 'likelihood_of_occurence', 'impact_of_occurence', 'overall_risk_rating', 'risk_category', 'treatment_option', 'treatment_option_details', 'recommended_control', 'applicable_annexture', 'risk_treatment_option_b', 'risk_treatment_plan_residual', 'control_effectiveness_level', 'revised_impact_data', 'revised_likelihood_rationale', 'revised_likelihood_of_occurence', 'revised_impact_of_occurence', 'revised_overall_risk_rating', 'revised_risk_category', 'key_risk_indicator', 'comments'];

    protected $fillable = ['module', 'risk_register_id', 'client_id', 'business_unit_id', 'business_process_id', 'project_id', 'asset_type_id', 'asset_id', 'impact_data', 'impact_on_areas', 'likelihood_of_occurence', 'likelihood_rationale', 'impact_of_occurence', 'impact_rationale', 'risk_score', 'risk_level', 'risk_level_color', 'treatment_option', 'treatment_option_details', 'recommended_control', 'control_effectiveness_level', 'revised_impact_data', 'revised_impact_on_areas', 'revised_likelihood_of_occurence', 'revised_likelihood_rationale', 'revised_impact_of_occurence', 'revised_impact_rationale', 'revised_risk_score', 'revised_risk_level', 'revised_risk_level_color', 'residual_risk_treatment_option', 'residual_treatment_option_details', 'timeline', 'responsible', 'status', 'residual_plan_present_status', 'target_closure_date', 'key_risk_indicator', 'comments'];
    protected function impactData(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function impactOnAreas(): Attribute
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
    protected function revisedImpactOnAreas(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function asset(): Attribute
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
    public function riskRegister()
    {
        return $this->belongsTo(RiskRegister::class, 'risk_register_id', 'id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    public function keyRiskIndicatorAssessment()
    {
        return $this->hasOne(KeyRiskIndicatorAssessment::class);
    }
}
