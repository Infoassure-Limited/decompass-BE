<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PersonalDataAssessment extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'standard_id', 'business_unit_id', 'business_process_id', 'personal_data_item', 'description', 'sensitive_personal_data', 'exception_used_personal_data', 'obtained_from_data_source', 'owner', 'processing_purpose', 'lawful_basis_of_processing', 'how_is_consent_obtained', 'automated_decision_making', 'level_of_data_subject_access', 'location_stored', 'country_stored_in', 'retention_period', 'encryption_level', 'access_control', 'third_parties_shared_with', 'comments'];

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
    protected function personalDataItem(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
