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


    public function saveDefaultBiaTimeRecoveryRequirement($client_id)
    {
        $impact_criteria = BiaTimeRecoveryRequirement::where([
            'client_id' => $client_id
        ])->count();
        if ($impact_criteria < 1) {

            $default_time_requirements = defaultBiaTimeRecoveryRequirement();
            foreach ($default_time_requirements as $default_time_requirement) {
                BiaTimeRecoveryRequirement::firstOrCreate([
                    'client_id' => $client_id,
                    'name' => $default_time_requirement['name'],
                    'time_in_minutes' => $default_time_requirement['time_in_minutes'],
                ]);
            }
        }
    }
}
