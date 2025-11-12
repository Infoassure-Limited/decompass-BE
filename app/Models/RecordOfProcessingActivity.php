<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordOfProcessingActivity extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'standard_id', 'business_unit_id', 'controller_name', 'controller_contact_details', 'joint_controller_name', 'joint_controller_contact_details', 'controller_rep_name', 'controller_rep_contact_details', 'dpo_name', 'dpo_details', 'processing_purpose', 'data_subject_categories', 'personal_data_categories', 'data_recipients_categories', 'international_transfer_destination', 'erasure_time_limit', 'security_measures_applied', 'comments'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
