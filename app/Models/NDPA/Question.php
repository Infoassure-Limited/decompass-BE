<?php

namespace App\Models\NDPA;

use App\Models\NDPA\Clause;
use App\Models\Standard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'ndpa';
    protected $fillable = ['section_id', 'clause_id', 'question', /*'question_type',*/ 'answer_type', 'hint', 'expected_document_template_ids'];
    protected $casts = ['expected_document_template_ids' => 'array'];
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function section()
    {
        return $this->belongsTo(ClauseSection::class, 'section_id', 'id');
    }
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
    protected function expectedDocumentTemplateIds(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
