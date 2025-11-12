<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    //
    protected $connection = 'vdd';
    //
    protected $fillable = ['vendor_id', 'title', 'link'];
}
