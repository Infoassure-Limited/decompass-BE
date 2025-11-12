<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['standard_id', 'clause_id', 'question', /*'question_type',*/ 'answer_type'];
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
