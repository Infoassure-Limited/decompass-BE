<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'company_email',
        'company_name',
        'phone_no',
        'job_function',
        'job_level',
        'country',
        'subject',
        'other_subject',
        'message',
        'date',
        'time'
    ];
}
