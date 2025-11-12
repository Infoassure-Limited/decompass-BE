<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCertificate extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'project_id'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // public function create(Project $project)
    // {
    //     $project_id  = $project->id;
    //     $certificate = ProjectCertificate::where(['client_id' => $project->client_id, 'project_id' => $project_id])->first();

    //     if (!$certificate) {
    //         $certificate = new ProjectCertificate();
    //         $certificate->client_id = $project->client_id;
    //         $certificate->project_id = $project_id;
    //         $certificate->save();
    //     }
    // }
}
