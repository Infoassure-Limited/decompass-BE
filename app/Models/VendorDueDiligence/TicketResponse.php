<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{

    protected $connection = 'vdd';
    protected $fillable = ['vendor_id', 'client_id', 'ticket_id', 'message', 'response_by'];


}
