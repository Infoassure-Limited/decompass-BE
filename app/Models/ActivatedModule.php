<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ActivatedModule extends Model
{
    // use SoftDeletes;
    protected $fillable = ['partner_id', 'available_module_id'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function availableModule()
    {
        return $this->belongsTo(AvailableModule::class);
    }
}
