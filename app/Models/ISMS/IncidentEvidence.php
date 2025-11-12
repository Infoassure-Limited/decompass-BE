<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class IncidentEvidence extends Model
{
    //
    protected $connection = 'isms';
    protected $table = 'incident_evidences';
    protected $fillable = [
        'client_id',
        'incident_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'comments',
    ];

}
