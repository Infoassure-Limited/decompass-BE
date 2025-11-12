<?php

namespace App\Models\NDPA;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ModuleActivityTask extends Model
{
    protected $connection = 'ndpa';
    protected $fillable = [
        'clause_id',
        'section_id',
        'activity_no',
        'name',
        'evidences',
        'document_template_ids',
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
    public function section()
    {
        return $this->belongsTo(ClauseSection::class, 'section_id', 'id');
    }
    // public function activity()
    // {
    //     return $this->belongsTo(ModuleActivity::class, 'module_activity_id', 'id');
    // }

    public function assignedTask()
    {
        return $this->hasOne(AssignedTask::class, 'module_activity_task_id', 'id');
    }
    protected function evidences(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
    protected function implementationGuide(): Attribute
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
