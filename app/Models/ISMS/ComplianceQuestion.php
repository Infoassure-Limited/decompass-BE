<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class ComplianceQuestion extends Model
{
    //
    protected $connection = 'isms';
    protected $fillable = ['clause_id', 'question', 'possible_tasks', 'input_type', 'select_options', 'is_multiple_select', 'requires_evidence'];


    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }

    protected function possibleTasks(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
