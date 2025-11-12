<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOAControl extends Model
{
    use HasFactory;
    public function area()
    {
        return $this->belongsTo(SOAArea::class, 's_o_a_area_id', 'id');
    }
    public function soa()
    {
        return $this->hasOne(StatementOfApplicability::class, 's_o_a_control_id', 'id');
    }
}
