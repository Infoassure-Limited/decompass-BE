<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class ComplianceResponse extends Model
{
    //
    protected $connection = 'isms';
    protected $fillable = ['assignee_id', 'client_id', 'compliance_response_monitor_id', 'clause_id', 'compliance_question_id', 'response', 'response_array', 'details', 'is_exception', 'status', 'evidences', 'assignee_tasks'];

    protected function assigneeTasks(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function question()
    {
        return $this->belongsTo(ComplianceQuestion::class, 'compliance_question_id', 'id');
    }

    protected function responseArray(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

}
