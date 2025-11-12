<?php

namespace App\Models\BCMS;

use App\Models\BusinessProcess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BIARiskAssessment extends Model
{
    use HasFactory;
    protected $connection = 'bcms';
    public function businessProcess()
    {
        return $this->belongsTo(BusinessProcess::class, 'business_process_id', 'id');
    }
}
