<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'course_of_interest'
    ];
}
