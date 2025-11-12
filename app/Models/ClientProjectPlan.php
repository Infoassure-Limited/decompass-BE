<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProjectPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'partner_id', 'client_id', 'project_id', 'general_project_plan_id', 'project_phase_id'
    ];
    public function projectPhase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }
    public function generalProjectPlan()
    {
        return $this->belongsTo(GeneralProjectPlan::class);
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
