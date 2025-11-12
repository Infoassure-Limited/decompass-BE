<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clause extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'standard_id', 'will_have_audit_questions', 'requires_document_upload'
    ];
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function templates()
    {
        return $this->hasMany(DocumentTemplate::class, 'clause_id', 'id');
    }
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
}
