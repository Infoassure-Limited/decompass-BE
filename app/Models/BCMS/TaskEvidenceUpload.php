<?php

namespace App\Models\BCMS;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class TaskEvidenceUpload extends Model
{
    //  
    protected $connection = 'bcms';

    protected $fillable = [
        'client_id',
        'project_id',
        'title',
        'expected_task_evidence_id',
        'created_by',
        'last_modified_by',
        'link',
        'is_exception'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
