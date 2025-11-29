<?php

namespace App\Models\PerfomanceAndMonitoring;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicableObjective extends Model
{
    use HasFactory;
    protected $connection = 'pam';
    protected $casts = [
        'metadata' => 'array',
    ];
    protected $fillable = ['client_id', 'title','description', 'weight', 'metadata', 'active'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function monitorings()
    {
        return $this->hasMany(PerformanceMonitoring::class, 'applicable_objective_id', 'id');
    }
}