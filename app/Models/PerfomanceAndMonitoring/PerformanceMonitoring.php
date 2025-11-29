<?php

namespace App\Models\PerfomanceAndMonitoring;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceMonitoring extends Model
{
    use HasFactory;
    protected $connection = 'pam';
    protected $fillable = ['client_id', 'info_need_id', 'measure_id','applicable_objective_id','frequency','unit','formula','target','is_achieved','not_achieved_reasons','corrective_actions','settings'];

    protected $casts = [
        'settings' => 'array',
    ];

    public function infoNeed()
    {
        return $this->belongsTo(InfoNeed::class, 'info_need_id');
    }

    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function applicableObjective()
    {
        return $this->belongsTo(ApplicableObjective::class, 'applicable_objective_id', 'id');
    }
}
