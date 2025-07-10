<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Story, Task, JournalStorySubmissions, Invoice, ExtraServices, SchedulePayment, Packages};
use Mail;
use \PDF;
use Illuminate\Support\Facades\{Hash, DB, Validator};
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function signUp(Request $request)
    {
        
        $user = new User;
        $user->date = date('Y-m-d');
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email =  $request->email;
        $user->phone =  $request->phone;
        $user->penName = $request->penName;
        $user->street =  $request->street;
        $user->city =  $request->city;
        $user->state =  $request->state;
        $user->country =  $request->country;
        $user->zipCode =  $request->zipCode;
        $user->hearAboutUs =  $request->hearAboutUs;
        $password =  $request->firstName . time();
        $user->password = Hash::make($password);
        $user->status = 1;
        $user->roleAs = $request->roleAs;
        $user->save();
        $files = [];
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '01submitit10' . $file->getClientOriginalName();
                $file->move(public_path() . '/files', $fileName);
                array_push($files, $fileName);
            }
        }
        $story = new Story();
        $story->files = implode(',', $files);
        $story->user_id = $user->id;
        $story->clientName = $request->clientName;
        $story->storyTitle = $request->storyTitle;
        $story->storyGenre = $request->storyGenre;
        $story->wordCount = $request->wordCount;
        $story->notesFromClient = $request->notesFromClient;
        $story->save($request->except($request->submissionPackageId, $request->fictionPackageId, $request->editingPackageId, $request->superPackageId));
        $packagesData = [$request->submissionPackageId, $request->fictionPackageId, $request->editingPackageId, $request->superPackageId];
        $packages = array_filter($packagesData, function ($element) {
            return $element !== 'null' && !empty($element);
        });
        $payment = [];
        $payment['email'] = User::find($story->user_id)->email;
        $payment['packages'] = implode(',', $packages);
        $payment['paymentLink'] = Str::random(64);
        $payment['story_id'] = $story->id;
        SchedulePayment::create($payment);
        $data = [['title' => 'Invoice Sent', 'date' => date('Y-m-d h:i:s'), 'type' => 'Automate', 'email_template_id' => 1, 'duration' => 0, 'status' => 1, 'round' => 1, 'story_id' => $story['id']], ['title' => 'Invoice Paid', 'date' => date('Y-m-d h:i:s', strtotime("+2 seconds")), 'type' => 'Automate', 'email_template_id' => 1, 'duration' => 0, 'status' => 2, 'round' => 1, 'story_id' => $story['id']]];
        Task::insert($data);
        $packagesData = [];
        foreach ($packages as $package) {
            array_push($packagesData, Packages::where('stripe_package_id', $package)->first());
        }
        dispatch(new \App\Jobs\PaymentJob($user['email'], $user, $password, 1));
        dispatch(new \App\Jobs\PaymentJob($payment['email'], $payment, $packagesData, 0));

        return response()->json(['message' => 'Client Registered Successfully', 'paymentLink' => $payment['paymentLink']]);
    }
    public function checkEmail(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    public function clientStories(Request $request)
    {
        $clientStory = Story::where('user_id', $request->id)->get();
        foreach ($clientStory as $key => $story) {
            $clientStory[$key]['submitted'] = count(JournalStorySubmissions::where('story_id', $story->id)->get());
            $clientStory[$key]['accepted'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 1)->get());
            $clientStory[$key]['withdrawn'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 2)->get());
            $clientStory[$key]['rejected'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 3)->get());
        }
        return response()->json($clientStory);
    }

    public function editClientStory(Request $request)
    {
        $story = Story::find($request->id);
        return response()->json($story);
    }

    public function updateClientStory(Request $request)
    {
        $files = [];
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '01submitit10' . $file->getClientOriginalName();
                $file->move(public_path() . '/files', $fileName);
                array_push($files, $fileName);
            }
        }
        if ($request->get('images')) {
            foreach ($request->get('images') as $image) {
                array_push($files, $image);
            }
        }
        $story = $request->except(['images', 'doNotSubmitList', 'submissionPackageId', 'fictionPackageId', 'editingPackageId', 'extraServicesId']);
        $story['files'] = implode(',', $files);
        Story::find($request->id)->update($story);
        return response()->json($story);
    }
    public function getAllInvoices(Request $request)
    {
        $invoices = Invoice::where('user_id', $request->id)->get();
        return response()->json($invoices);
    }
    public function downloadInvoice(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $user = User::find($invoice['user_id']);
        $package = Packages::where('stripe_package_id', $invoice['stripe_package_id'])->first();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('invoicePdf', ['invoice' => $invoice, 'user' => $user, 'package' => $package]);
        return $pdf->output();
    }

    public function getPendingPayment(Request $request)
    {
        $services = ExtraServices::where('user_id', $request->id)->where('status', "Pending")->get()->toArray();
        foreach ($services as $key => $service) {
            $services[$key]['story_id'] = Story::where('id', $service['story_id'])->first();
        }
        $storyId = Story::where('user_id', $request->id)->pluck('id');
        $pending = SchedulePayment::whereIn('story_id', $storyId)->where('status', "Pending")->get()->toArray();
        foreach ($pending as $key => $service) {
            $pending[$key]['story_id'] = Story::where('id', $service['story_id'])->first();
            $packageId = explode(',', $service['packages']);
            $pending[$key]['packages'] = Packages::whereIn('stripe_package_id', $packageId)->get();
            array_push($services, (array)$pending[$key]);
        }
        return response()->json($services);
    }
    public function getActiveSubscription(Request $request)
    {
        $invoice = Invoice::where('user_id', $request->id)->where('created_at', '>=', \Carbon\Carbon::today()->subDays(30))->whereNotIn('stripe_package_id', ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"])->get()->toArray();
        if(User::find($request->id)->superPackage != NULL)
        {
            array_push($invoice, Invoice::where('user_id', $request->id)->where('stripe_package_id', User::find($request->id)->superPackage)->first());
        }
        foreach ($invoice as $key => $service) {
            $invoice[$key]['story_id'] = Story::where('id', $service['story_id'])->first();
            $invoice[$key]['stripe_package_id'] = Packages::where('stripe_package_id', $service['stripe_package_id'])->first();
        }
        return response()->json($invoice);
    }
}
