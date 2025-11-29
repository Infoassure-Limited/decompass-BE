<?php

namespace App\Models\PerfomanceAndMonitoring;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoNeedCategory extends Model
{
    use HasFactory;
    protected $connection = 'pam';
    protected $fillable = ['name','slug'];

    public function infoNeeds()
    {
        return $this->hasMany(InfoNeed::class);
    }
}
