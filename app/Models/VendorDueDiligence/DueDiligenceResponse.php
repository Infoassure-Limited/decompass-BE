<?php

namespace App\Models\VendorDueDiligence;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DueDiligenceResponse extends Model
{
    use HasFactory;
    protected $connection = 'vdd';
    protected $fillable = ['client_id', 'vendor_id', 'due_diligence_question_id', 'is_submitted'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function question()
    {
        return $this->belongsTo(DueDiligenceQuestion::class, 'due_diligence_question_id', 'id');
    }
    public function evidences()
    {
        return $this->hasMany(DueDiligenceEvidence::class, 'due_diligence_response_id', 'id');
    }

    public function riskAssessment()
    {
        return $this->hasOne(RiskAssessment::class, 'due_diligence_response_id', 'id');
    }
}
