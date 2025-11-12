<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\VendorDueDiligence\Invoice;
use App\Models\VendorDueDiligence\InvoiceItem;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    //
    public function index(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $invoices = Invoice::query();
        $condition = [];
        if (isset($request->invoice_no) && $request->invoice_no != '') {
            $invoice_no = $request->invoice_no;
            $invoices->where('invoice_no', $invoice_no);
        }
        if (isset($request->amount) && $request->amount != '') {
            $amount = $request->amount;
            $invoices->where('amount', $amount);
        }
        if (isset($request->due_date) && $request->due_date != '') {
            $due_date = $request->due_date;
            $invoices->where('due_date', $due_date);
        }
        if (isset($request->status) && $request->status != '') {
            $status = $request->status;
            $invoices->where('status', $status);
        }

        if (isset($request->invoice_approval) && $request->invoice_approval != '') {
            $invoice_approval = $request->invoice_approval;
            $invoices->where('invoice_approval', 'LIKE', '%"' . $invoice_approval . '"%');
        }


        $invoices = $invoices->with('invoiceItems')
            ->where('vendor_id', $vendor_id)
            ->orderBy('due_date')
            ->orderBy('status')
            ->paginate($request->limit);
        return response()->json(compact('invoices'), 200);
    }

    public function store(Request $request)
    {
        $invoice_items = json_decode(json_encode($request->invoice_items));
        $vendor_id = $request->vendor_id;
        $invoice_no = $request->invoice_no;
        $vendor = Vendor::find($vendor_id);

        $invoice = Invoice::where(['invoice_no' => $invoice_no, 'vendor_id' => $vendor_id])->first();
        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->client_id = $vendor->client_id;
            $invoice->vendor_id = $vendor_id;
            $invoice->invoice_no = $invoice_no;
            $invoice->amount = $request->amount;
            $invoice->subtotal = $request->subtotal;
            $invoice->discount = $request->discount;
            $invoice->due_date = $request->due_date;
            $invoice->notes = $request->notes;
            if ($invoice->save()) {
                $this->saveInvoiceItems($invoice, $invoice_items);

                // notify the client
                $token = $request->bearerToken();
                $user = User::where('api_token', $token)->first();
                $name = $user->name;// . ' (' . $user->email . ')';
                $title = "Invoice generated for payment";
                // $userIds = $client->users()->pluck('id')->toArray();
                $userIds = $this->getVendorClientUserIds($vendor_id);
                //log this event
                $description = "$name generated an invoice with number $invoice_no for payment. <br>";
                $this->sendNotification($title, $description, $userIds);


                return response()->json(['message' => "success"], 200);
            }
        }
        return response()->json(['message' => "Invoice $invoice_no already exists."], 500);

    }
    public function uploadInvoice(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $invoice_no = $request->invoice_no;
        $vendor = Vendor::find($vendor_id);
        $file = $request->file('file_uploaded');
        $invoice = Invoice::where(['invoice_no' => $invoice_no, 'vendor_id' => $vendor_id])->first();
        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->client_id = $vendor->client_id;
            $invoice->vendor_id = $vendor_id;
            $invoice->invoice_no = $invoice_no;
            $invoice->amount = $request->amount;
            $invoice->subtotal = $request->amount; // $request->subtotal;
            $invoice->discount = 0; // $request->discount;
            $invoice->due_date = $request->due_date;
            $invoice->notes = null; // $request->notes;

            if ($invoice->save()) {
                if ($file->isValid()) {
                    $formated_name = str_replace(' ', '_', ucwords($invoice_no));
                    $file_name = 'invoice_' . $formated_name . '_' . $vendor_id . "." . $file->guessClientExtension();
                    $link = $file->storeAs('vendors/' . $vendor_id . '/invoices', $file_name, 'public');
                    $invoice->invoice_link = $link;
                    $invoice->save();

                    // notify the client
                    $token = $request->bearerToken();
                    $user = User::where('api_token', $token)->first();
                    $name = $user->name;// . ' (' . $user->email . ')';
                    $title = "Invoice generated for payment";
                    // $userIds = $client->users()->pluck('id')->toArray();
                    $userIds = $this->getVendorClientUserIds($vendor_id);
                    //log this event
                    $description = "$name generated an invoice with number $invoice_no for payment. <br>";
                    $this->sendNotification($title, $description, $userIds);

                    // $this->auditTrailEvent($title, $description, $users);

                    return response()->json(['message' => "success"], 200);
                }
            }
        }
        return response()->json(['message' => "Invoice $invoice_no already exists."], 500);

    }

    private function saveInvoiceItems($invoice, $invoice_items)
    {
        $subtotal = 0;
        $discount = $invoice->discount;
        foreach ($invoice_items as $invoice_item) {


            $invoiceItem = new InvoiceItem();
            if ($invoice_item->id != NULL) {

                $invoiceItem = InvoiceItem::find($invoice_item->id);
            }
            $invoiceItem->vendor_id = $invoice->vendor_id;
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->description = $invoice_item->description;
            $invoiceItem->quantity = $invoice_item->quantity;
            $invoiceItem->rate = $invoice_item->rate;
            $invoiceItem->amount = $invoice_item->amount;
            $invoiceItem->save();
            $subtotal += $invoice_item->amount;
        }

        $total = $subtotal - $discount;
        $invoice->subtotal = $subtotal;
        $invoice->amount = $total;
        $invoice->save();
    }
    public function update(Request $request, Invoice $invoice)
    {
        $vendor = Vendor::find($invoice->vendor_id);
        $invoice_items = json_decode(json_encode($request->invoice_items));
        $invoice->amount = $request->amount;
        $invoice->subtotal = $request->subtotal;
        $invoice->discount = $request->discount;
        $invoice->due_date = $request->due_date;
        $invoice->notes = $request->notes;
        if ($invoice->save()) {
            $this->saveInvoiceItems($invoice, $invoice_items);

            $token = $request->bearerToken();
            $user = User::where('api_token', $token)->first();
            $name = $user->name;// . ' (' . $user->email . ')';
            $title = "Invoice $invoice->invoice_no modified";
            // $userIds = $client->users()->pluck('id')->toArray();
            $userIds = $this->getVendorClientUserIds($vendor->id);
            //log this event
            $description = "$name modified the details of invoice with number $invoice->invoice_no. <br>";
            $this->sendNotification($title, $description, $userIds);
        }
        // notify the client
    }
    public function approvalAction(Request $request, Invoice $invoice)
    {
        $user = $this->getUser();
        $client = $this->getClient();
        $field = $request->field;
        $approval = [
            'action' => $request->action,
            'details' => ($request->details) ? $request->details : null,
            'approved_by' => $user->name,
            'date' => date('Y-m-d H:i:s', strtotime('now')),
        ];
        $invoice->invoice_approval = $approval;
        $invoice->save();
        $vendor = Vendor::find($invoice->vendor_id);

        //  send notifications accordingly after the final approval action
        $actioned = ($request->action === 'Approve') ? 'Approved' : 'Rejected';
        $details = ($request->details) ? 'Reasons: ' . $request->details : '';
        $vendorUserIds = User::where('vendor_id', $vendor->id)->pluck('id')->toArray();


        $title = "Invoice $actioned";
        //log this event
        $description = "$user->name from $client->name has reviewed and " . strtolower($actioned) . " invoice with number $invoice->invoice_no. <br>" .
            $details;
        //log this event
        // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
        $this->sendVendorNotification($title, $description, $vendorUserIds);


        $invoice = $invoice->with('invoiceItems')->find($invoice->id);
        return response()->json(compact('invoice'), 200);
    }
    public function uploadPaymentEvidence(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);
        $file = $request->file('file_uploaded');
        if ($file->isValid()) {
            $formated_name = str_replace(' ', '_', ucwords($invoice->invoice_no));
            $file_name = 'payment_evidence_for_invoice_' . $formated_name . '_' . $invoice->vendor_id . "." . $file->guessClientExtension();
            $link = $file->storeAs('vendors/' . $invoice->vendor_id . '/invoices', $file_name, 'public');
            $invoice->payment_evidence = $link;
            $invoice->save();

            // $this->auditTrailEvent($title, $description, $users);
            $invoice = $invoice->with('invoiceItems')->find($invoice->id);
            return response()->json(compact('invoice'), 200);
        }
    }
    public function makePayment(Request $request, Invoice $invoice)
    {
        $user = $this->getUser();
        $client = $this->getClient();

        $invoice->status = 'Paid';
        $invoice->payment_date = date('Y-m-d', strtotime('now'));
        $invoice->save();

        $vendorUserIds = User::where('vendor_id', $invoice->vendor_id)->pluck('id')->toArray();


        $title = "Payment made for Invoice $invoice->invoice_no";
        //log this event
        $description = "$user->name from $client->name has made payment for invoice $invoice->invoice_no. <br> Kindly confirm that you have received the payment on the portal.";
        //log this event
        // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
        $this->sendVendorNotification($title, $description, $vendorUserIds);

        $invoice = $invoice->with('invoiceItems')->find($invoice->id);
        return response()->json(compact('invoice'), 200);
        // notify the vendor
    }

    public function confirmPayment(Request $request, Invoice $invoice)
    {
        $invoice->is_confirmed = 1;
        $invoice->save();

        $vendor = Vendor::find($invoice->vendor_id);
        // $client = Client::with('users')->find($vendor->client_id);
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        $name = $user->name;// . ' (' . $user->email . ')';
        $title = "Payment for invoice $invoice->invoice_no confirmed";
        // $userIds = $client->users()->pluck('id')->toArray();
        $userIds = $this->getVendorClientUserIds($vendor->id);
        //log this event
        $description = "$name confirmed the receipt of payment made for invoice with number $invoice->invoice_no. <br>";
        $this->sendNotification($title, $description, $userIds);

        // notify the client

        $invoice = $invoice->with('invoiceItems')->find($invoice->id);
        return response()->json(compact('invoice'), 200);
    }
    public function destroyInvoiceItem(Request $request, InvoiceItem $invoice_item)
    {
        $invoice = Invoice::find($invoice_item->invoice_id);
        if ($invoice->status == 'Pending') {
            $invoice_item->delete();
        }
        $remaining_invoice_items = $invoice->invoiceItems;
        $subtotal = 0;
        $discount = $invoice->discount;
        foreach ($remaining_invoice_items as $invoice_item) {
            $subtotal += $invoice_item->amount;
        }

        $total = $subtotal - $discount;
        $invoice->subtotal = $subtotal;
        $invoice->amount = $total;
        $invoice->save();
        // notify the client
    }
    public function destroy(Request $request, Invoice $invoice)
    {
        if ($invoice->status == 'Pending') {

            $vendor = Vendor::find($invoice->vendor_id);
            // $client = Client::with('users')->find($vendor->client_id);
            $token = $request->bearerToken();
            $user = User::where('api_token', $token)->first();
            $name = $user->name;// . ' (' . $user->email . ')';
            $title = "Pending invoice $invoice->invoice_no deleted";
            // $userIds = $client->users()->pluck('id')->toArray();
            $userIds = $this->getVendorClientUserIds($vendor->id);
            //log this event
            $description = "$name deleted a pending invoice with number $invoice->invoice_no. <br>";
            $this->sendNotification($title, $description, $userIds);

            $invoice->invoiceItems()->delete();
            $invoice->delete();
        }
        // notify the client
    }
}
