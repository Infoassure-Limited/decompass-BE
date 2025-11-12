<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'vdd';
    protected $fillable = ['question', 'key', 'domain', 'answer_type', 'upload_evidence'];

}
