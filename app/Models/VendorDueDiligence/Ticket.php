<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Ticket extends Model
{
    protected $connection = 'vdd';
    protected $fillable = ['vendor_id', 'client_id', 'ticket_no', 'subject', 'message', 'priority', 'status', 'created_by', 'is_resolved', 'assigned_to', 'category'];

    public function responses()
    {
        return $this->hasMany(TicketResponse::class, 'ticket_id', 'id');
    }

}
