<?php

namespace App\Models\NDPA;

use App\Models\Client;
use App\Models\Project;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['client_id', 'assignee_id', 'section_id', 'project_id', 'question_id', 'clause_id', 'created_by'];

    protected $connection = 'ndpa';
    /**
     * Get the user that owns the Answer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function section()
    {
        return $this->belongsTo(ClauseSection::class, 'section_id', 'id');
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function evidences()
    {
        return $this->hasMany(GapAssessmentEvidence::class, 'answer_id', 'id');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param   $data: array of field to be populated and their values
     * @return void
     */
    public function createProjectAnswer($data)
    {
        Answer::firstOrCreate($data);
    }
}
