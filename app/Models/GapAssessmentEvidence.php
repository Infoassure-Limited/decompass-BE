<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GapAssessmentEvidence extends Model
{
    use HasFactory, SoftDeletes;
    protected  $table = 'gap_assessment_evidences';

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
