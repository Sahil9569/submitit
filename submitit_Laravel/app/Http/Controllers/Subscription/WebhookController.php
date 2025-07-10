<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Invoice, Packages, Story, Task};
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public $user;
    public function handleWebhook(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        http_response_code(400);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        http_response_code(400);
        exit();
        }

        
        $user = User::where('stripe_id', $event->data->object->customer)->first();
        $this->user = $user;
        $story = Story::where('user_id', $user['id'])->latest()->first();
        $plan = $event->data->object->plan->id;
        $package = Packages::where('stripe_package_id', $plan)->first();
        $task = Task::where('title', 'Email Updated Story')->where('story_id', $story['id'])->latest()->first();
        switch ($event->type) {
        case 'customer.subscription.created':
            $invoice = new Invoice;
            if($package['type']=='submission')
            {
                $invoice->packageName = 'Submission Package => '.$package['title'];
            }
            else if($package['type']=='flash')
            {
                $invoice->packageName = 'Flash Fiction Package => '.$package['title'];
            }
            else if($package['type']=='editing')
            {
                $invoice->packageName = 'Editing and Consulting Package => '.$package['title'];
            }
            else if($package['type']=='super')
            {
                $invoice->packageName = 'Super Package => '.$package['title'];
            }
            else
            {
                $invoice->packageName = 'Extra Package => '.$package['title'];
            }
            $invoice->stripe_package_id = $package->stripe_package_id;
            $invoice->transactionId = $event->data->object->latest_invoice;
            $invoice->amount = $package->amount;
            $invoice->startFrom = date('Y-m-d h:i:s', $event->data->object->current_period_start);
            $invoice->expiredAt = date('Y-m-d h:i:s', $event->data->object->current_period_end);
            $invoice->status = 1;
            $invoice->user_id = $user->id;
            $invoice->subscriptionId = $event->data->object->id;
            $invoice->story_id = $story['id'];
            $invoice->type='normal';
            $invoice->save();
            if($package->type=='submission')
            {
                $story->submissionPackageId = $package->id;
            }
            else if($package->type=='flash')
            {
                $story->fictionPackageId = $package->id;
            }
            else
            {
                $story->editingPackageId = $package->id;
            }
            $story->update();
            $task->email_template_id = (($story['fictionPackageId']!=null?1:$story['submissionPackageId']!=null)?2:3);
            $task->update();
            Mail::to($user->email)->send(new \App\Mail\InvoiceMail($user, $package, $invoice));
            break;
        case 'subscription_schedule.canceled':
            break;
        case 'customer.subscription.deleted':
            break;
        case 'customer.subscription.updated':
            $invoice = new Invoice;
            if($package['type']=='submission')
            {
                $invoice->packageName = 'Submission Package => '.$package['title'];
            }
            else if($package['type']=='flash')
            {
                $invoice->packageName = 'Flash Fiction Package => '.$package['title'];
            }
            else if($package['type']=='editing')
            {
                $invoice->packageName = 'Editing and Consulting Package => '.$package['title'];
            }
            else if($package['type']=='super')
            {
                $invoice->packageName = 'Super Package => '.$package['title'];
            }
            else
            {
                $invoice->packageName = 'Extra Package => '.$package['title'];
            }
            $invoice->stripe_package_id = $package->stripe_package_id;
            $invoice->transactionId = $event->data->object->latest_invoice;
            $invoice->amount = $package->amount;
            $invoice->startFrom = date('Y-m-d h:i:s', $event->data->object->current_period_start);
            $invoice->expiredAt = date('Y-m-d h:i:s', $event->data->object->current_period_end);
            $invoice->status = 1;
            $invoice->user_id = $user->id;
            $invoice->subscriptionId = $event->data->object->id;
            $invoice->story_id = $story['id'];
            $invoice->type='normal';
            $invoice->save();
            if($package->type=='submission')
            {
                $story->submissionPackageId = $package->id;
            }
            else if($package->type=='flash')
            {
                $story->fictionPackageId = $package->id;
            }
            else
            {
                $story->editingPackageId = $package->id;
            }
            $story->update();
            Mail::to($user->email)->send(new \App\Mail\InvoiceMail($user, $package, $invoice));
            break;
            case 'payment_intent.succeeded':
                $invoice = new Invoice;
                $invoice->packageName = $event->data->object->description;
                $invoice->stripe_package_id = 'Custom Package';
                $charge = \Stripe\Charge::retrieve($event->data->object->latest_charge);
                $transactionId = $charge->balance_transaction;
                $invoice->transactionId = $transactionId;
                $invoice->amount = $event->data->object->amount/100;
                $invoice->startFrom = date('Y-m-d');
                $invoice->expiredAt = date('Y-m-d');
                $invoice->status = 1;
                $invoice->user_id = User::where('stripe_id', $event->data->object->customer)->first->stripe_id;
                $invoice->subscriptionId = $event->data->object->id;
                $invoice->story_id = $story['id'];
                $invoice->type='extra';
                $invoice->save();
                break;
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        $user->update();
        http_response_code(200);
    }
}