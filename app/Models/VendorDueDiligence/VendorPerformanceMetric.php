<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;

class VendorPerformanceMetric extends Model
{
    //
    protected $connection = 'vdd';
    protected $fillable = ['client_id', 'vendor_id', 'contract_id', 'sla_config_id', 'metrics', 'target', 'unit', 'hint', 'modify', 'unit_disabled', 'rating_frequency'];
    protected function scoreCards()
    {
        return $this->hasMany(VendorPerformanceScorecard::class, 'vendor_performance_metric_id', 'id');
    }
}
