<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Partner extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function activatedModules()
    {
        return $this->hasMany(ActivatedModule::class, 'partner_id', 'id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
