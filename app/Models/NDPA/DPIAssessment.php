<?php

namespace App\Models\NDPA;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DPIAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'business_unit_id', 'business_process_id', 'personal_data_asset', 'risk_scenerio', 'risk_owner', 'existing_controls', 'likelihood', 'likelihood_rationale', 'impact', 'impact_rationale', 'risk_score', 'risk_level', 'treatment_option', 'treatment_option_details', 'treatment_actions', 'revised_likelihood', 'revised_likelihood_rationale', 'revised_impact', 'revised_impact_rationale', 'revised_risk_score', 'revised_risk_level', 'comments'];

    protected function treatmentOptionDetails(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
