<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InvoiceItem extends Model
{
    use SoftDeletes;
    //
    protected $connection = 'vdd';
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
