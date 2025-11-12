<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'mysql';
    protected $fillable = [
        'title',
        'partner_id',
        'client_id',
        'available_module_id',
        'subscription_id',
        'module_package_id',
        'year'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function availableModule()
    {
        return $this->belongsTo(AvailableModule::class);
    }
    public function package()
    {
        return $this->belongsTo(ModulePackage::class, 'module_package_id', 'id');
    }
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    public function consulting()
    {
        return $this->belongsTo(Consulting::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function consultants()
    {
        return $this->belongsToMany(User::class, 'project_consultant', 'project_id', 'user_id');
    }
    public function certificate()
    {
        return $this->hasOne(ProjectCertificate::class, 'project_id', 'id');
    }
    public function clientProjectPlans()
    {
        return $this->hasMany(ClientProjectPlan::class, 'project_id', 'id');
    }

    public function watchProjectProgress(Project $project)
    {
        // $project_id  = $project->id;
        // $uploaded_documents = Upload::where(['project_id' => $project_id, 'is_exception' => 0])->where('link', '!=', NULL)->count();
        // $expected_documents = Upload::where(['project_id' => $project_id])->count();
        // $answered_questions = Answer::where(['project_id' => $project_id, 'is_exception' => 0])->where('is_submitted', 1)->count();
        // $all_questions = Answer::where(['project_id' => $project_id])->count();

        // $total_task = $expected_documents + $all_questions;
        // $total_response = $uploaded_documents + $answered_questions;
        // $percentage_progress = 0;
        // if ($total_task > 0) {
        //     $percentage_progress = $total_response / $total_task * 100;
        // }

        // $project->progress = $percentage_progress;
        // $project->save();
    }
}
