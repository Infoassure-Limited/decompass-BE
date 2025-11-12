<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskImpact extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'name', 'value', 'matrix'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function impactOnAreas()
    {
        return $this->hasMany(RiskImpactOnArea::class, 'risk_impact_id', 'id');
    }
}
