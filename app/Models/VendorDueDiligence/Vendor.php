<?php

namespace App\Models\VendorDueDiligence;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $connection = 'vdd';

    protected $fillable = ['client_id', 'admin_user_id', 'name', 'business_type', 'other_business_type', 'contact_name', 'contact_email', 'contact_phone', 'contact_address', 'reg_no', 'country_of_incorporation', 'website', 'years_in_business', 'stores_sentivite_information', 'has_access_to_critical_systems', 'has_impact_on_business_operations', 'service_description', 'work_with_similar_organization', 'references_to_working_with_similar_organizations', 'have_business_insurance', 'business_insurance_file_link', 'business_license_link', 'list_of_clients_or_industry_recognitions', 'does_subcontract_services', 'list_of_services_subcontracted', 'industry_certifications', 'past_regulatory_compliance_violations', 'details_of_compliance_violations', 'internal_compliance_team_or_officer', 'have_formal_cybersecurity_policy', 'cyber_security_policy_link', 'have_recent_data_breach', 'data_breach_resolution_process', 'ensure_data_protection_and_confidentiality', 'does_background_checks_on_employees', 'company_tax_identification_no', 'ongoing_legal_dispute', 'legal_dispute_details'];
    /**
     * The roles that belong to the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bankDetail()
    {
        return $this->hasOne(BankDetail::class, 'vendor_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function documentUploads()
    {
        return $this->hasMany(DocumentUpload::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function reviewMeetings()
    {
        return $this->hasMany(ReviewMeeting::class);
    }

    protected function clientUsers(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function firstApproval(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    protected function secondApproval(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
