<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Story, Packages, StoryScore, Task, DefaultTask, Job, Invoice, SchedulePayment, User, Journals, ExtraServices, JournalStorySubmissions};
use App\Jobs\TaskAutomate;
use Mail;
use DB;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function storeStory(Request $request)
    {
        $files = [];
        $story = $request->except(['submissionPackageId', 'fictionPackageId', 'editingPackageId', 'paymentLink', 'useAvailablePackage']);
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '01submitit10' . $file->getClientOriginalName();
                $file->move(public_path() . '/files', $fileName);
                array_push($files, $fileName);
            }
        }
        if ($request->useAvailablePackage == 'true') {
            $invoices = Invoice::where('user_id', $request->user_id)->where('expiredAt', '>=', date('Y-m-d h:i:s'))->pluck('story_id')->toArray();
            $stories = Story::whereIn('id', $invoices)->get();
            foreach ($stories as $storyData) {
                if ($storyData['submissionPackageId'] != null) {
                    $story['submissionPackageId'] = $storyData['submissionPackageId'];
                }
                if ($storyData['fictionPackageId'] != null) {
                    $story['fictionPackageId'] = $storyData['fictionPackageId'];
                }
                if ($storyData['editingPackageId'] != null) {
                    $story['editingPackageId'] = $storyData['editingPackageId'];
                }
            }
        }
        $story['files'] = implode(',', $files);
        $story['finalVersionSaved'] = $story['finalVersionSaved'] == 1 ? 1 : 0;
        $story['wordCountChecked'] = $story['wordCountChecked'] == 1 ? 1 : 0;

           foreach ($story as $field => $value) {
            if ($story[$field]=='null') {
                $story[$field] = null;
            }
        }

// Store $data in the database

        $story = Story::create($story);
        if ($request->useAvailablePackage == 'true' && User::find($story['user_id'])->superPackage==NULL) {
            $this->addTask(User::find($story['user_id']), $story['id'], (($story['fictionPackageId'] != null ? 'fiction' : $story['submissionPackageId'] != null) ? 'submission' : 'editing'));
        } 
        else if(User::find($story['user_id'])->superPackage==NULL){
            $packagesData = [$request->submissionPackageId, $request->fictionPackageId, $request->editingPackageId];
            $packages = array_filter($packagesData, function($element) {
                return $element !== 'null';
              });
            $payment = [];
            $payment['email'] = User::find($story->user_id)->email;
            $payment['packages'] = implode(',', $packages);
            $payment['paymentLink'] = Str::random(64);
            $payment['story_id'] = $story->id;
            SchedulePayment::create($payment);
            $packagesData = [];
            foreach ($packages as $package) {
                array_push($packagesData, Packages::where('stripe_package_id', $package)->first());
            }
            // $data=[['title'=>'Invoice Paid', 'date' => date('Y-m-d h:i:s'), 'type'=>'Automate', 'email_template_id'=>1, 'duration'=>0, 'status'=>2, 'round'=>1, 'story_id'=>$story['id']]];
            // Task::insert($data);
            // dispatch(new \App\Jobs\PaymentJob($payment['email'], $payment, $packagesData, 0));
        }
        return response()->json('Story Saved Successfully');
    }
    public function getAllStories()
    {
        $stories = Story::orderBy('id', 'DESC')->get();
        foreach ($stories as $key => $story) {
            $stories[$key]['lastTask'] = Task::where('story_id', $story->id)->where('status', 1)->orderBy('date', 'DESC')->first();;
            $stories[$key]['nextTask'] = Task::where('story_id', $story->id)->where('status', 2)->orderBy('date', 'ASC')->first();
        }
        return response()->json($stories);
    }
    public function getSingleStory(Request $request)
    {
        $story = Story::find($request->id);
        $extraServices = [];
        $story['submissionPackage'] = Packages::find($story->submissionPackageId);
        $story['fictionPackage'] = Packages::find($story->fictionPackageId);
        $story['editingPackage'] = Packages::find($story->editingPackageId);
        $schedulePayment = SchedulePayment::where('story_id', $request->id)->first();
        $story['submitted'] = count(JournalStorySubmissions::where('story_id', $story->id)->get());
        $story['accepted'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 1)->get());
        $story['withdrawn'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 2)->get());
        $story['rejected'] = count(JournalStorySubmissions::where('story_id', $story->id)->where('status', 3)->get());
        if($story['extraServicesId']!=NULL)
        {
            foreach(explode(',', $story['extraServicesId'])as $key => $service)
            {
                $extraServices[$key] =  ExtraServices::find($service);
            }
        }
        if (StoryScore::where('story_id', $request->id)->exists()) {
            $storyScore = StoryScore::where('story_id', $request->id)->first();
        } else {
            $storyScore = null;
        }
        return response()->json(['story' => $story, 'storyScore' => $storyScore, 'extraServices' => count($extraServices)>0?$extraServices:[], 'superPackage' => User::find($story['user_id'])->superPackage]);
    }
    public function getRecentStory()
    {
        $stories = Story::orderBy('id', 'DESC')->limit(10)->get();
        return response()->json($stories);
    }
    public function deleteStory(Request $request)
    {
        if (StoryScore::where('story_id', $request->id)->exists()) {
            $storyScore = StoryScore::where('story_id', $request->id)->first();
            $storyScore->delete();
        }
        if (JournalStorySubmissions::where('story_id', $request->id)->exists()) {
            JournalStorySubmissions::where('story_id', $request->id)->delete();
        }
        if (SchedulePayment::where('story_id', $request->id)->exists()) {
            SchedulePayment::where('story_id', $request->id)->delete();
        }
        if (ExtraServices::where('story_id', $request->id)->exists()) {
            ExtraServices::where('story_id', $request->id)->delete();
        }
        if (Invoice::where('story_id', $request->id)->exists()) {
            Invoice::where('story_id', $request->id)->delete();
        }
        if (Task::where('story_id', $request->id)->exists()) {
            $taskIdArray = Task::where('story_id', $request->id)->pluck('id')->toArray();
            if (Job::whereIn('task_id', $taskIdArray)->exists()) {
                Job::whereIn('task_id', $taskIdArray)->delete();
            }
            $tasks = Task::where('story_id', $request->id)->delete();
        }
        $story = Story::find($request->id);
        $story->delete();
        return response()->json('Story Deleted Successfully');
    }

    public function editStory(Request $request)
    {
        $story = Story::find($request->id);
        $story->bio = User::find($story->user_id)->bio;
        return response()->json($story);
    }
    public function updateStory(Request $request)
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
        $story = $request->except(['images', 'submissionPackageId', 'fictionPackageId', 'editingPackageId', 'extraServicesId', 'superPackage']);
        $story['files'] = implode(',', $files);
        $story['finalVersionSaved'] = $story['finalVersionSaved'] ? 1 : 0;
        $story['wordCountChecked'] = $story['wordCountChecked'] ? 1 : 0;
        $story['strategyNotes'] =  $request->strategyNotes;
        foreach ($story as $field => $value) {
            if ($story[$field]=='null') {
                $story[$field] = null;
            }
        }
        Story::find($request->id)->update($story);
        return response()->json($story);
    }
    public function getClientLimit(Request $request)
    {
        $user = ['journalLimit' => 0, 'storyLimit' => 0, 'stories'=>[]];
        $invoices = Invoice::where('user_id', $request->id)->where('expiredAt', '>=', date('Y-m-d h:i:s'))->where('type', 'normal')->get();
        $submittedStory = JournalStorySubmissions::where('user_id', $request->id)->where('created_at', '>=', \Carbon\Carbon::today()->subDays(30))->get();
        foreach ($invoices as $invoice) {
            $user['journalLimit'] = $user['journalLimit'] + Packages::where('stripe_package_id', $invoice->stripe_package_id)->first()->journalLimit;
            $user['storyLimit'] = $user['storyLimit'] + Packages::where('stripe_package_id', $invoice->stripe_package_id)->first()->storyLimit;
        }
        $user['journalLimit'] = $user['journalLimit'] - count(collect($submittedStory)->pluck('journal_id'));
        $user['storyLimit'] = $user['storyLimit'] - count(array_unique(collect($submittedStory)->pluck('story_id')->toArray()));
        $user['stories'] =  collect($submittedStory)->pluck('story_id');
        $superPackage = null;
        if(User::find($request->id)->superPackage != NULL)
        {
            $superPackage = User::find($request->id)->superPackage;
        }
        return response()->json(['user'=> $user, 'superPackage'=> $superPackage]);
    }
    public function getSelectedPackages(Request $request)
    {
        $package = [];
        if(SchedulePayment::where('paymentLink', $request->link)->exists())
        {
            $package = SchedulePayment::where('paymentLink', $request->link)->first();

        }
        return response()->json($package);
    }
    public function addTask($user, $storyId, $packageType)
    {
        $story = Story::where('user_id', $user['id'])->latest()->first();
        $totalStory = count(Story::where('user_id', $user['id'])->get());
        Task::insert($this->defaultTask($storyId, $totalStory > 1 ? 0 : 1, $packageType));
        $this->automateTask($storyId);
    }
    public function defaultTask($id = null, $isSignUp = 0, $packageType)
    {
        $defaultTask = [];
        $days = 0;
        if ($isSignUp == 1) {
            $tasks = DefaultTask::select('title', 'email_template_id', 'duration', 'task_sequence', 'type', 'round')->orderBy('task_sequence', 'ASC')->get()->toArray();
        } else {
            $tasks = DefaultTask::where('id', '!=', 1)->select('title', 'email_template_id', 'duration', 'task_sequence', 'type', 'round')->orderBy('task_sequence', 'ASC')->get()->toArray();
        }
        foreach ($tasks as $task) {
            $taskData = [];
            $days += $task['duration'];
            $taskData['title'] = $task['title'];
            if($task['title']=='Submissions 1')
            {
                $taskData['date'] = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + ' . $days . 'days'. 10 . ' minutes'));
            }
            else
            {
                $taskData['date'] = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + ' . $days . 'days'. 1 . ' minutes'));
            }
            $taskData['duration'] = $task['duration'];
            if($task['email_template_id'])
            {
                $taskData['email_template_id'] = $task['email_template_id'];
            }
            else
            {
                $taskData['email_template_id'] = NULL;
            }
            $taskData['type'] = $task['type'];
            $taskData['round'] = $task['round'];
            $taskData['story_id'] =  $id;
            array_push($defaultTask, $taskData);
        }
        return $defaultTask;
    }
    public function automateTask($storyId)
    {
        $tasks = Task::where('story_id', $storyId)->get();
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
    }
    public function getDoNotSubmitJournals(Request $request)
    {
        $doNotSubmitList = User::find($request->id)->doNotSubmitList;
        $storyDoNotSubmitList = Story::find($request->storyId)->doNotSubmitList;
        $arrClientDoNotSubmitList = explode(',', $doNotSubmitList);
        $arrStoryDoNotSubmitList = explode(',', $storyDoNotSubmitList);
        $pendingJournals = JournalStorySubmissions::where('user_id', $request->id)->where('status', 0)->get()->pluck('journal_id')->toArray();
        $rejectJournals = JournalStorySubmissions::where('user_id', $request->id)->where('status', 3)->get()->pluck('journal_id')->toArray();
        $journals = Journals::whereIn('id', array_merge($arrClientDoNotSubmitList, $arrStoryDoNotSubmitList, $pendingJournals))->get()->toArray();
        $otherJournal = Journals::whereIn('id', array_merge($rejectJournals))->get()->toArray();
        return response()->json(['journals'=>$journals, 'otherJournals'=>$otherJournal]);
    }
    public function getClientDoNotSubmitJournals(Request $request)
    {
        $doNotSubmitList = User::find($request->id)->doNotSubmitList;
        $journals = Journals::whereIn('id', explode(',', $doNotSubmitList))->get();
        return response()->json($journals);
    }
    public function putDoNotSubmitList(Request $request)
    {
        $journalId = JournalStorySubmissions::where('user_id', $request->id)->where('status', 3)->where('round', 3)->pluck('journal_id')->toArray();
        if(count($journalId)>0)
        {
            $user = User::find($request->id);
            $oldSubmitList = explode(',', $user->doNotSubmitList);
            $submitList = array_merge($oldSubmitList, $journalId);
            $unique_items = [];
            foreach ($submitList as $item) {
                if (!is_string($item) && !is_int($item)) {
                    continue;
                }
                $string_item = (string) $item;
                $unique_items[$string_item] = true;
            }
            $user->doNotSubmitList =  implode(',', array_keys($unique_items));
            $user->update();
        }
        return response()->json('Submit List Updated Successfully');
    }
    public function addInDoNotSubmitList(Request $request)
    {
        $doNotSubmitList = [];
        $story = Story::find($request->storyId);
        if($story['doNotSubmitList'])
        {
            $doNotSubmitList = explode(',', $story['doNotSubmitList']);
        }
        array_push($doNotSubmitList, $request->id);
        $story['doNotSubmitList'] = implode(',', $doNotSubmitList);
        $story->update();
        return response()->json('Do Not Submit List Updated Successfully');
    }
    public function removeSubmissionJournal(Request $request)
    {
        $journal = JournalStorySubmissions::find($request->id);
        $journal->delete();
        return response()->json('Journal Submission Deleted Successfully');
    }
    public function rejectSubmissionJournal(Request $request)
    {
        $journal = JournalStorySubmissions::find($request->id);
        $journal->status=3;
        $journal->update();
        return response()->json('Journal Status Changed Successfully');
    }
    public function approvePayment(Request $request)
    {
        $payment = SchedulePayment::find($request->id);
        $payment['status']='Success';
        $payment->update();
        $story = Story::find($payment['story_id']);
        $user = User::find($story['user_id']);
        foreach(explode(',', $payment['packages']) as $package)
        {
            $packageData = Packages::where('stripe_package_id', $package)->first();
            $invoice = new Invoice;
            if($packageData['type']=='submission')
            {
                $story['submissionPackageId'] =  $packageData['id'];
                $invoice['packageName'] =  'Submission Package => '.$packageData['title'];
            }
            else if($packageData['type']=='flash')
            {
                $story['fictionPackageId'] =  $packageData['id'];
                $invoice['packageName'] =  'Flash Fiction Package => '.$packageData['title'];
            }
            else if($packageData['type']=='editing')
            {
                $story['editingPackageId'] =  $packageData['id'];
                $invoice['packageName'] =  'Editing and Consulting Package => '.$packageData['title'];
            }
            else
            {
                $user['superPackage'] = $packageData['stripe_package_id'];
                $invoice['packageName'] =  'Super Package => '.$packageData['title'];
            }
            $invoice['stripe_package_id'] = $package;
            $invoice['transactionId']='By Cash/Cheque';
            $invoice['amount'] = $packageData['amount'];
            $invoice['startFrom']= date('Y-m-d h:i:s');
            $invoice['expiredAt']= date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s'). ' + 30 days'));
            $invoice['status']= 1;
            $invoice['user_id']= Story::find($payment['story_id'])->user_id;
            $invoice['subscriptionId']= 'NULL';
            $invoice['story_id']= $payment['story_id'];
            $invoice['type']= 'normal';
            $invoice->save();
        }
        // $task = Task::where('story_id', $payment['story_id'])->where('title', 'Invoice Paid')->first();
        // $task->status = 1;
        // $task->update();
        $story->update();
        $user->update();
        $this->addTask(User::find(Story::find($payment['story_id'])->user_id), $payment['story_id'], 'editing');
        return response()->json('Payment Updated Successfully');
    }
}