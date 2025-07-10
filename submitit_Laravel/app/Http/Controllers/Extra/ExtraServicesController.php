<?php

namespace App\Http\Controllers\Extra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Task, Job, User, UserExtraServices, Story};
use App\Jobs\TaskAutomate;
use App\Models\ExtraServices;
use Illuminate\Support\Str;
use Stripe;

class ExtraServicesController extends Controller
{
    public function addRound3(Request $request)
    {
        $round3Task = [
            ['title' => 'Algorithm 3 + Email', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + 0 days')), 'duration' => 0, 'email_template_id' => 11, 'type' => 'Manual', 'story_id' => $request->story_id, 'round' => 3],
            ['title' => 'Submissions 3', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . '2 days')), 'duration' => 2, 'email_template_id' => 11, 'type' => 'Manual', 'story_id' => $request->story_id, 'round' => 3],
            ['title' => 'Email Check-In', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . '30 days')), 'duration' => 28, 'email_template_id' => 11, 'type' => 'Automate', 'story_id' => $request->story_id, 'round' => 3],
            ['title' => 'Email Check-In', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . '60 days')), 'duration' => 28, 'email_template_id' => 11, 'type' => 'Automate', 'story_id' => $request->story_id, 'round' => 3],
            ['title' => 'Email Check-In', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . '90 days')), 'duration' => 28, 'email_template_id' => 11, 'type' => 'Automate', 'story_id' => $request->story_id, 'round' => 3],
            ['title' => 'Email Check-In', 'date' => date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . '120 days')), 'duration' => 28, 'email_template_id' => 11, 'type' => 'Automate', 'story_id' => $request->story_id, 'round' => 3],
        ];
        Task::insert($round3Task);
        $tasks = Task::where('story_id', $request->story_id)->where('round', 3)->get();
        $days = 0;
        foreach ($tasks as $task) {
            $days += $task->duration;
            if ($task->type == 'Automate') {
                $date1 = new \DateTime(date('d-m-Y H:i:s'));
                $date2 = new \DateTime(date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + ' . $days . 'days')));
                $delay = $date2->getTimestamp() - $date1->getTimestamp();
                TaskAutomate::dispatch(['task_id' => $task->id])->delay(now()->addSeconds($delay));
                $job = Job::orderBy('id', 'desc')->first();
                $jobData = Job::find($job->id);
                $jobData->task_id = $task->id;
                $jobData->update();
            }
        }
        return response()->json('Round 3 Task Has Been Added Successfully');
    }
    public function createExtraAddOn(Request $request)
    {
        $extraService = new ExtraServices;
        $extraService->title = $request->title;
        $extraService->amount = $request->amount;
        $extraService->token = Str::random(64);
        $extraService->story_id = $request->story_id;
        $extraService->user_id = $request->user_id;
        $extraService->save();
        $story = Story::find($request->story_id);
        $story->extraServicesId = $story->extraServicesId != NULL?$story->extraServicesId.','.$extraService->id:$extraService->id;
        $story->update();
        $userEmail = User::find($request->user_id)->email;
        $payment['paymentLink'] = $extraService->token;
        $services[0] = $extraService;
        // dispatch(new \App\Jobs\PaymentJob($userEmail, $payment, $services, 0));
        return response()->json('Service Created Successfully');
    }
    public function extraService(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $service = ExtraServices::where('token', $request->serviceToken)->first();
        $user = User::find($request->id);
        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int)$service['amount'] * 100,
                'customer' => $user['stripe_id'],
                'currency' => 'usd',
                'description' => $service['title'],
            ]);
            return response()->json(['success' => true, 'paymentIntent' => $paymentIntent]);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function confirmPaymentIntent(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntentId = $request->input('payment_intent_id');
        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
            if ($paymentIntent->status === 'requires_confirmation') {
                $paymentIntent->confirm();
            }
            $service = ExtraServices::where('token', $request->token)->first();
            $service->status = 'Success';
            $service->update();
            return response()->json(['success' => true]);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getServices()
    {
        $services = ExtraServices::all();
        foreach($services as $key => $service)
        {
            $services[$key]['story_id'] = Story::find($service['story_id']);
            $services[$key]['user_id'] = User::find($service['user_id']);
        }
        return response()->json($services);
    }
}