<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherUnitsUser extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'business_unit_id', 'email', 'name'];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
