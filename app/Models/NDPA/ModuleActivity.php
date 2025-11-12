<?php

namespace App\Models\NDPA;

use Illuminate\Database\Eloquent\Model;

class ModuleActivity extends Model
{
    protected $connection = 'ndpa';
    protected $fillable = [
        'clause_id',
        'section_id',
        'activity_no',
        'name',
        'description'
    ];
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function section()
    {
        return $this->belongsTo(ClauseSection::class, 'section_id', 'id');
    }
    public function tasks()
    {
        return $this->hasMany(ModuleActivityTask::class, 'module_activity_id', 'id');
    }
}
