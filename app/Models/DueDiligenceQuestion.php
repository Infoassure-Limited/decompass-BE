<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DueDiligenceQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['question', 'key', 'domain'];
    public function response()
    {
        return $this->hasOne(DueDiligenceResponse::class, 'due_diligence_question_id', 'id');
    }
}
