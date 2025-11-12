<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSubscriptionDetail extends Model
{
    //
    protected $fillable = ['client_id', 'available_module_id', 'subscription_id', 'module_package_id', 'amount'];

    public function availableModule()
    {
        return $this->belongsTo(AvailableModule::class);
    }

    public function modulePackage()
    {
        return $this->belongsTo(ModulePackage::class);
    }
}
