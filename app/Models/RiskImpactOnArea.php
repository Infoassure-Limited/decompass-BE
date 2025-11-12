<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskImpactOnArea extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'risk_impact_id', 'impact_value', 'risk_impact_area_id', 'impact_level', 'matrix'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function impactArea()
    {
        return $this->belongsTo(RiskImpactArea::class, 'risk_impact_area_id', 'id');
    }
}
