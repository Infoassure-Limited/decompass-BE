<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSubscription extends Model
{
    protected $fillable = ['client_id', 'amount', 'discount', 'total', 'year'];

    public function details()
    {
        return $this->hasMany(PackageSubscriptionDetail::class, 'subscription_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(PackageSubscriptionPayment::class, 'subscription_id', 'id');
    }
}
