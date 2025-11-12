<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralProjectPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'task',
        'responsibility',
        'resource',
        'project_phase_id'
    ];
    // public function standards()
    // {
    //     return $this->belongsToMany(Standard::class);
    // }
    public function projectPhase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }
    public function clientProjectPlan()
    {
        return $this->hasMany(Client::class);
    }
    public function availableModules()
    {
        return $this->belongsToMany(AvailableModule::class);
    }
}
