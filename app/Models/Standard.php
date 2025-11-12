<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'consulting_id',
        'assessment_activities'
    ];
    public function consulting()
    {
        return $this->belongsTo(Consulting::class);
    }
    public function clauses()
    {
        return $this->hasMany(Clause::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function generalProjectPlans()
    {
        return $this->belongsToMany(GeneralProjectPlan::class);
    }
}
