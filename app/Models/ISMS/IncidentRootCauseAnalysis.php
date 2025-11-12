<?php

namespace App\Models\ISMS;

use Illuminate\Database\Eloquent\Model;

class IncidentRootCauseAnalysis extends Model
{
    //
    protected $connection = 'isms';

    protected $fillable = [
        'client_id',
        'description',
        'incident_id',
        'impact_of_the_incident',
        'preventive_measures',
        'follow_up_required',
        'method',
        'created_by'
    ];
}
