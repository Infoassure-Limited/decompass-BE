<?php

namespace App\Models\VendorDueDiligence;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class VendorPerformanceScorecard extends Model
{
    //
    protected $connection = 'vdd';
    protected $fillable = ['contract_id', 'client_id', 'vendor_id', 'sla_config_id', 'vendor_performance_metric_id', 'scores', 'comments', 'approval_status'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function sla()
    {
        return $this->belongsTo(SlaConfig::class, 'sla_config_id', 'id');
    }
    public function kpiMetrics()
    {
        return $this->belongsTo(VendorPerformanceMetric::class, 'vendor_performance_metric_id', 'id');
    }
    protected function scores(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
