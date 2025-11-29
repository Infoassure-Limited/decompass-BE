<?php

namespace App\Models\PerfomanceAndMonitoring;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    protected $connection = 'pam';

    protected $casts = [
        'metadata' => 'array',
    ];
    //
    protected $fillable = ['info_need_id', 'title','measurement_need', 'metadata'];

    public function infoNeed()
    {
        return $this->belongsTo(InfoNeed::class);
    }
}
