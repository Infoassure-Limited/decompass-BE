<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvailableModule extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function activatedModules()
    {
        return $this->hasMany(ActivatedModule::class);
    }
    public function standards()
    {
        return $this->hasMany(Standard::class);
    }
    public function generalProjectPlans()
    {
        return $this->belongsToMany(GeneralProjectPlan::class);
    }
    public function features()
    {
        return $this->hasMany(ModuleFeature::class, 'available_module_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany(ModulePackage::class, 'available_module_id', 'id');
    }
}
