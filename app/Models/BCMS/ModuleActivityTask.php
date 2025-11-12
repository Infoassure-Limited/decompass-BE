<?php

namespace App\Models\BCMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ModuleActivityTask extends Model
{
    protected $connection = 'bcms';
    protected $fillable = [
        'clause_id',
        'activity_no',
        'name',
        'evidences',
        'document_template_ids',
        'tasks',
        'dependency',
        'description',
        'implementation_guide',
        'priority',
        'occurence'
    ];
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    // public function activity()
    // {
    //     return $this->belongsTo(ModuleActivity::class, 'module_activity_id', 'id');
    // }

    public function assignedTask()
    {
        return $this->hasOne(AssignedTask::class, 'module_activity_task_id', 'id');
    }

    protected function tasks(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    protected function documentTemplateIds(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    public function expectedTaskEvidences()
    {
        return $this->belongsToMany(ExpectedTaskEvidence::class);
    }

}
