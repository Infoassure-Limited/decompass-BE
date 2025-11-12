<?php

namespace App\Models\BCMS;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProcessDisruptionImpact extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'bcms';
    protected $fillable = ['client_id', 'business_impact_analysis_id', 'criteria', 'process_disruption_impact'];
    protected function processDisruptionImpact(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
