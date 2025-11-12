<?php

namespace App\Models\BCMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpectedTaskEvidence extends Model
{
    use SoftDeletes;
    protected $connection = 'bcms';
    protected $table = 'expected_task_evidences';
    protected $fillable = [
        'title'
    ];

    public function upload()
    {
        return $this->hasOne(TaskEvidenceUpload::class);
    }
}
