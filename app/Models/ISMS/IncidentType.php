<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    protected $connection = 'isms';
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
