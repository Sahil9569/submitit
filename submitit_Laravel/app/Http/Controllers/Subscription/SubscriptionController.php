<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe;
use App\Models\{Invoice, User, Packages, SchedulePayment, Story, Task};
use App\Http\Controllers\Story\StoryController;

class SubscriptionController extends Controller
{
    public function getSetupIntent(Request $request)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $stripe_data =  $stripe->setupIntents->create([
            'payment_method_types' => ['card'],
        ]);
        return response()->json($stripe_data);
    }
    public function createSubscription(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $customer = $stripe->customers->all(array(
            'email' => $request->email,
            'limit' => 1
        ));
        if (!empty($customer['data'])) {
            $user = $stripe->customers->retrieve($customer['data'][0]['id'], []);
        } 
        else {
            $user = $stripe->customers->create(['name' => 'Submitit Customer','email' => $request->email, 'address' => ['line1' => 'Near Building', 'line2' => 'Tower', 'city' => 'New York', 'state' => 'New York', 'postal_code' => '10001', 'country' => 'US'],
                'payment_method' => $request->paymentId, 'invoice_settings' => [
                'default_payment_method' => $request->paymentId,
            ],]);
            $userData = User::where('email', $request->email)->first();
            $userData['stripe_id'] = $user->id;
            $userData->save();
        }
        $planIDs = $request->get('plans');
        foreach($planIDs as $planID)
        {
            $stripe->subscriptions->create([
                'customer' => $user,
                'items' => [
                    ['price' => $planID],
                ],
                'collection_method' => 'charge_automatically',
                'payment_behavior' => 'allow_incomplete',
                'off_session' => true
            ]);
        }
        $schedulePayment = SchedulePayment::where('paymentLink', $request->paymentLink)->first();
        $schedulePayment->status = 'Success';
        $schedulePayment->update();
        $story = Story::find($schedulePayment['story_id']);
        if(User::find($story['user_id'])->roleAs!='super')
        {
            $storyCTR = new StoryController();
            $storyCTR->addTask(User::where('email', $request->email)->first(), $schedulePayment['story_id'], (($story['fictionPackageId']!=null?'fiction':$story['submissionPackageId']!=null)?'submission':'editing'));
        }
        if (Task::where('story_id', $schedulePayment['story_id'])->where('title', 'Invoice Paid')->where('status', 2)->exists()) {
            $task = Task::where('story_id', $schedulePayment['story_id'])->where('title', 'Invoice Paid')->where('status', 2)->first();
            $task->status = 1;
            $task->update();
        }
        return response()->json('Subscription Created Successfully');
    }
    public function getAllInvoice(Request $request)
    {
        $invoices = Invoice::where('user_id', $request->id)->get();
        return response()->json($invoices);
    }
    public function cancelSubscription(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Subscription::update(
            $request->subscriptionId,
            [
                'cancel_at_period_end' => true,
            ]
        );
        $invoice = Invoice::where('subscriptionId', $request->subscriptionId)->first();
        $invoice->isCanceled = 1;
        $invoice->update();
        return response()->json('Subscription Canceled Successfully');
    }
}
