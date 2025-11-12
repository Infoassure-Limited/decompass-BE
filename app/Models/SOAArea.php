<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOAArea extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    /**
     * Get all of the controls for the SOAArea
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function controls()
    {
        return $this->hasMany(SOAControl::class, 's_o_a_area_id', 'id');
    }
    public function soas()
    {
        return $this->hasMany(StatementOfApplicability::class, 's_o_a_area_id', 'id');
    }
}
