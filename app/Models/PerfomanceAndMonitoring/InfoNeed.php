<?php

namespace App\Models\PerfomanceAndMonitoring;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoNeed extends Model
{
    use HasFactory;
    protected $connection = 'pam';
    protected $fillable = ['info_need_category_id','name','code'];

    public function category()
    {
        return $this->belongsTo(InfoNeedCategory::class,'info_need_category_id');
    }
}
