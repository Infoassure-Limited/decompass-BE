<?php

namespace App\Models\BCMS;

use Illuminate\Database\Eloquent\Model;

class Clause extends Model
{
    //
    protected $connection = 'bcms';
    protected $fillable = ['sort_by', 'name', 'description'];
    public function tasks()
    {
        return $this->hasMany(ModuleActivityTask::class, 'clause_id', 'id');
    }
}
