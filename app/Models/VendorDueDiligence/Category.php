<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $connection = 'vdd';
    protected $fillable = ['client_id', 'name', 'slug', 'description'];
}
