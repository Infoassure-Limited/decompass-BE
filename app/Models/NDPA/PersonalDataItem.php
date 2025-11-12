<?php

namespace App\Models\NDPA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDataItem extends Model
{
    use HasFactory;
    protected $connection = 'ndpa';
    protected $fillable = ['item', 'client_id'];
}
