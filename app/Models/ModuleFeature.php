<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleFeature extends Model
{
    //
    public function availableModule()
    {
        return $this->belongsTo(AvailableModule::class);
    }
}
