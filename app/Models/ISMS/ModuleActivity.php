<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class ModuleActivity extends Model
{
    protected $connection = 'isms';
    protected $fillable = [
        'clause_id',
        'activity_no',
        'name',
        'description',
        'implementation_guide'
    ];
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function tasks()
    {
        return $this->hasMany(ModuleActivityTask::class, 'module_activity_id', 'id');
    }
}
