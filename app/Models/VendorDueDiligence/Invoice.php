<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Invoice extends Model
{
    use SoftDeletes;
    //
    protected $connection = 'vdd';
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected function invoiceApproval(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
