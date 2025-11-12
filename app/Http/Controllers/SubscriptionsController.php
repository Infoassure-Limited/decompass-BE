<?php

namespace App\Http\Controllers;

use App\Models\AvailableModule;
use App\Models\PackageSubscription;
use App\Models\PackageSubscriptionDetail;
use App\Models\PackageSubscriptionPayment;
use App\Models\Project;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function fetchSubscriptionDetails()
    {
        $year = date('Y', strtotime('now'));
        $client_id = $this->getClient()->id;
        $subscription = PackageSubscription::with('details.availableModule', 'details.modulePackage', 'payments')->where(
            [
                'client_id' => $client_id,
                'year' => $year,
            ]
        )->first();
        return response()->json(compact('subscription'), 200);
    }
    public function store(Request $request)
    {
        $cart_items = json_decode(json_encode($request->cart_items));
        $year = date('Y', strtotime('now'));
        $client_id = $this->getClient()->id;
        $amount = $request->amount;
        $discount = ($request->discount / 100) * $amount;
        $total = $amount - $discount;
        $new_subscription = PackageSubscription::updateOrCreate(
            [
                'client_id' => $client_id,
                'year' => $year,
            ],
            [
                'amount' => $amount,
                'discount' => $discount,
                'total' => $total,
            ]
        );
        $subscription = PackageSubscription::find($new_subscription->id);
        $this->createSubscriptionDetails($subscription, $cart_items);


        if ($subscription->total < 1) {
            $this->createProject($subscription->id);
        }
        return response()->json(compact('subscription'), 200);
    }

    private function createSubscriptionDetails($subscription, $cart_items)
    {
        PackageSubscriptionDetail::where([
            'client_id' => $subscription->client_id,
            'subscription_id' => $subscription->id
        ])->where('amount', 0)->delete();
        foreach ($cart_items as $item) {
            PackageSubscriptionDetail::updateOrCreate(
                [
                    'client_id' => $subscription->client_id,
                    'subscription_id' => $subscription->id,
                    'available_module_id' => $item->module_id,
                ],
                [
                    'module_package_id' => $item->package_id,
                    'amount' => $item->price,
                ]
            );
        }
    }

    public function paymentForSubscription(Request $request)
    {
        $client_id = $this->getClient()->id;
        $payment = new PackageSubscriptionPayment();
        $payment->client_id = $client_id;
        $payment->subscription_id = $request->subscription_id;
        $payment->amount = $request->amount;
        $payment->txn_ref = $request->reference;
        $payment->status = $request->status;
        $payment->message = $request->message;
        $payment->save();
        if ($payment->status == 'success') {
            // create project
            $this->createProject($payment->subscription_id);
            $this->updateSubscriptionStatus($payment->subscription_id, $payment->amount);
        }

    }
    private function createProject($subscriptionId)
    {
        $actor = $this->getUser();
        $client = $this->getClient();

        $partner_id = $client->partner_id;
        $sub_details = PackageSubscriptionDetail::where('subscription_id', $subscriptionId)->get();
        foreach ($sub_details as $sub_detail) {
            $available_module = AvailableModule::find($sub_detail->available_module_id);
            $project = Project::updateOrCreate([
                'title' => $available_module->name,
                'partner_id' => $partner_id,
                'client_id' => $client->id,
                'available_module_id' => $sub_detail->available_module_id,
                'subscription_id' => $subscriptionId,
                'year' => $this->getYear(),
            ], [
                'module_package_id' => $sub_detail->module_package_id,
            ]);
            $project->users()->syncWithoutDetaching([$actor->id]);
        }
        // $actor = $this->getUser();
        // $title = "New Project Created for Client";
        // //log this event
        // $description = "New project was created for $client->name by $actor->name";
        // $this->auditTrailEvent($title, $description);
    }
    private function updateSubscriptionStatus($subscriptionId, $amount)
    {
        $subscription = PackageSubscription::find($subscriptionId);
        $subscription->paid += $amount;
        $subscription->save();

    }
    public function sucessfulPaymentStatus(Request $request)
    {
        $successful_payment = PackageSubscriptionPayment::where(['subscription_id' => $request->subscription_id, 'status' => 'success', 'message' => 'Approved'])->first();
        return response()->json(compact('successful_payment'), 200);
    }
}
