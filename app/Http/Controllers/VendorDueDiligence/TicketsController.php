<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\Ticket;
use App\Models\VendorDueDiligence\TicketResponse;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    //
    public function index(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $tickets = Ticket::query();
        $condition = [];
        if (isset($request->ticket_no) && $request->ticket_no != '') {
            $ticket_no = $request->ticket_no;
            $tickets->where('ticket_no', $ticket_no);
        }
        if (isset($request->date) && $request->date != '') {
            $date = date('Y-m-d', strtotime($request->date));
            $tickets->where('created_at', 'LIKE', '%' . $date . '%');
        }
        if (isset($request->status) && $request->status != '') {
            $status = $request->status;
            $tickets->where('status', $status);
        }
        if (isset($request->priority) && $request->priority != '') {
            $priority = $request->priority;
            $tickets->where('priority', $priority);
        }

        if (isset($request->category) && $request->category != '') {
            $category = $request->category;
            $tickets->where('category', $category);
        }


        $tickets = $tickets->where(['vendor_id' => $vendor_id, 'client_id' => $client_id])
            ->orderBy('id')
            ->paginate($request->limit);
        return response()->json(compact('tickets'), 200);
    }

    public function show(Request $request, Ticket $ticket)
    {
        $ticket = $ticket->with([
            'responses' => function ($q) {
                $q->orderBy('id', 'DESC');
            }
        ])->find($ticket->id);
        return response()->json(compact('ticket'), 200);
    }

    public function store(Request $request)
    {
        $ticket = Ticket::orderBy('id', 'DESC')->first();
        $prepend_id = $ticket->id + 1;
        $ticket_no = 'TKT-' . $prepend_id . randomNumber();
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $data = $request->toArray();
        $data['client_id'] = $client_id;
        $data['ticket_no'] = $ticket_no;
        $ticket = Ticket::create($data);
        $message = "Message: " . $ticket->message;
        $created_by = $ticket->created_by;

        $vendorUserIds = User::where('vendor_id', $vendor_id)->pluck('id')->toArray();
        $title = "Issue Ticket Created";
        //log this event
        $description = "New issue ticket with ticket number $ticket_no has been opened by $created_by. <br>" . $message;
        //log this event
        $this->sendVendorNotification($title, $description, $vendorUserIds);
        // Log this action

        $userIds = $this->getVendorClientUserIds($vendor_id);
        $this->sendNotification($title, $description, $userIds);
        return 'success';
    }

    public function updateField(Request $request, Ticket $ticket)
    {
        $value = $request->value;
        $field = $request->field;
        $ticket->$field = $value;
        $ticket->save();
        return response()->json(compact('ticket'), 200);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        $ticket->delete();

        return 'success';
    }
    public function saveTicketResponse(Request $request)
    {
        $ticket_id = $request->ticket_id;
        $ticket = Ticket::find($ticket_id);
        $ticket->status = 'In Progress';
        $ticket->save();
        $ticket_no = $ticket->ticket_no;
        $data = $request->toArray();
        $response = TicketResponse::create($data);
        $message = "Message: " . $response->message;
        $response_by = $response->response_by;

        $vendorUserIds = User::where('vendor_id', $ticket->vendor_id)->pluck('id')->toArray();
        $title = "Response to Ticket $ticket_no";
        //log this event
        $description = "There is a response to ticket number $ticket_no by $response_by. <br>" . $message;
        $this->sendVendorNotification($title, $description, $vendorUserIds);
        // Log this action

        $userIds = $this->getVendorClientUserIds($ticket->vendor_id);
        $this->sendNotification($title, $description, $userIds);
        return 'success';
    }
}
