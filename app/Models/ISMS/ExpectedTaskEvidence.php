<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpectedTaskEvidence extends Model
{
    use SoftDeletes;
    //
    protected $connection = 'isms';
    protected $table = 'expected_task_evidences';
    protected $fillable = [
        'title'
    ];

    public function upload()
    {
        return $this->hasOne(TaskEvidenceUpload::class);
    }
}
