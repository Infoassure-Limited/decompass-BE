<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $connection = 'vdd';
    //
    protected $fillable = ['vendor_id', 'bank_name', 'account_name', 'account_no'];
}
