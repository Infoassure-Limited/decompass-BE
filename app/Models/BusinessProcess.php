<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BusinessProcess extends Model
{

    use HasFactory, SoftDeletes;
    protected $connection = 'mysql';
    protected $fillable = ['generated_process_id', 'client_id', 'business_unit_id', 'process_owner', 'teams', 'name', 'description', 'objective', 'roles_responsible', 'no_of_people_involved', 'minimum_no_of_people_involved', 'product_or_service_delivered', 'regulatory_obligations', 'applications_used', 'business_units_depended_on', 'processes_depended_on', 'key_vendors_or_external_dependencies', 'vital_non_electronic_records', 'vital_electronic_records', 'alternative_workaround_during_system_failure', 'key_individuals_process_depends_on', 'peak_periods', 'remote_working'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
    public function owner()
    {
        return $this->belongsTo(OtherUnitsUser::class, 'process_owner', 'id');
    }

    protected function teams(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }
    public function pdas()
    {
        return $this->hasMany(PersonalDataAssessment::class, 'business_process_id', 'id');
    }
    public function rcsas()
    {
        return $this->hasMany(RiskControlSelfAssessment::class, 'business_process_id', 'id');
    }
    public function riskRegisters()
    {
        return $this->hasMany(RiskRegister::class);
    }
}
