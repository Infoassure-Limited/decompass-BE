<?php

namespace App\Models\BCMS;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiaTimeRecoveryRequirement extends Model
{
    use HasFactory;
    protected $connection = 'bcms';
    protected $table = "bia_time_recovery_requirements";
    protected $fillable = ['client_id', 'name', 'time_in_minutes'];


}
