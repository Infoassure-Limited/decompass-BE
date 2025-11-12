<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DueDiligenceQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'vdd';
    protected $fillable = ['client_id', 'question', 'key', 'domain', 'answer_type', 'upload_evidence'];
    public function response()
    {
        return $this->hasOne(DueDiligenceResponse::class, 'due_diligence_question_id', 'id');
    }
}
