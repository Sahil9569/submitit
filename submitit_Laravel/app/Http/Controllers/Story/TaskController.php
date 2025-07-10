<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\JournalStorySubmissions;
use Illuminate\Http\Request;
use App\Models\{Task, EmailTemplates, Story, DefaultTask, Job, User, Journals};
use App\Jobs\TaskAutomate;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function getEmailTemplates()
    {
        $emailTemplates = EmailTemplates::all();
        return response()->json($emailTemplates);
    }
    public function addTask(Request $request)
    {
        $date = date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->story_id)->max('date') . ' + ' . $request->duration . 'days'));
        $task = new Task;
        $task->title = $request->title;
        $task->date = $date;
        $task->type = $request->type;
        $task->duration = $request->duration;
        $task->email_template_id = $request->email_template_id;
        $task->story_id = $request->story_id;
        $task->save();
        if ($request->type == 'Automate') {
            $date1 = new \DateTime(date('d-m-Y H:i:s'));
            $date2 = new \DateTime($date);
            $delay = $date2->getTimestamp() - $date1->getTimestamp();
            TaskAutomate::dispatch()->delay(now()->addSeconds($delay));
            $job = Job::orderBy('created_at', 'desc')->first();
            $jobData = Job::find($job->id);
            $jobData->task_id = $task->id;
            $jobData->update();
        }
    }
    public function getTasks(Request $request)
    {
        // $tasks = Task::where('story_id', $request->story_id)->orderBy('id', 'ASC')->orderBy('date', 'ASC')->orderBy('status', 'DESC')->orderBy('updated_at', 'ASC')->get();
        $tasks = Task::where('story_id', $request->story_id)->orderBy('date', 'ASC')->get();
        return response()->json($tasks);
    }
    public function getAllTasks()
    {
        $tasks = Task::Join('stories', 'tasks.story_id', '=', 'stories.id')->join('users', 'users.id', '=', 'stories.user_id')->select('users.firstName', 'users.lastName', 'users.id as user_id', 'tasks.*', 'stories.storyTitle')->get();
        return response()->json($tasks);
    }
    public function getClientStoryTasks(Request $request)
    {
        $stories = Story::where('user_id', $request->id)->select('id')->get()->toArray();
        $storyIds = array_column($stories, 'id');
        $tasks = Task::whereIn('story_id', $storyIds)->get();
        return $tasks;
    }
    public function editTask(Request $request)
    {
        $task = Task::find($request->id);
        return response()->json($task);
    }
    public function deleteTask(Request $request)
    {
        $task = Task::find($request->id);
        $storyId = $task->story_id;
        if (Job::where('task_id', $request->id)->exists()) {
            $job = Job::where('task_id', $request->id)->first();
            $job->delete();
        }
        $task->delete();
        $this->updateAllTask($storyId);
        return response()->json('Task Deleted Successfully');
    }
    public function updateTask(Request $request)
    {
        $task = Task::find($request->id);
        if($request->status==1)
        {
            $oldDate = date_create($request->enabledDate);
            $newDate = date_create($request->date);
            $diff = date_diff($oldDate, $newDate);
            if($oldDate->format('Y-m-d') === $newDate->format('Y-m-d'))
            {
                $duration = 0;
            }
            else
            {   
                $duration = (int)$diff->format("%a")+1;
            }
            $task->duration = $duration;
            $task->date = date('Y-m-d h:i:s', strtotime($request->enabledDate . ' + ' . $duration . 'days'));
            $task->update();
        }
        else
        {
            $task->title = $request->title;
            $date = date('Y-m-d h:i:s', strtotime($task->date . ' - ' . $task->duration . 'days'));
            $task->type = $request->type;
            $task->email_template_id = $request->email_template_id;
            $task->duration = $request->duration;
            $task->story_id = $request->story_id;
            if ($request->type == 'Automate') {
                if (Job::where('task_id', $task->id)->exists()) {
                    $job = Job::where('task_id', $task->id)->first();
                    $jobData = Job::find($job->id);
                    $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $request->duration . 'days')));
                    $jobData->update();
                } else {
                    $date1 = new \DateTime(date('Y-m-d h:i:s'));
                    $date2 = new \DateTime(date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + ' . $request->duration . 'days')));
                    $delay = $date2->getTimestamp() - $date1->getTimestamp();
                    TaskAutomate::dispatch()->delay(now()->addSeconds($delay));
                    $job = Job::orderBy('created_at', 'desc')->first();
                    $jobData = Job::find($job->id);
                    $jobData->task_id = $task->id;
                    $jobData->update();
                }
            }
            $task->update();
            if ($request->type == 'Manual') {
                if (Job::where('task_id', $task->id)->exists()) {
                    $job = Job::where('task_id', $task->id)->delete();
                }
            }
        }
        $this->updateAllTask($request->story_id);
        return response()->json('Task Updated Successfully');
    }
    public function updateAllTask($id, $date='')
    {
        $tasks = Task::where('story_id', $id)->orderBy('date', 'ASC')->get();
        $durations = collect($tasks)->pluck('duration')->toArray();
        $time = false;
        $days = 0;
        if (count($durations) > 0) {
            if($date=='')
            {
                $date = date('Y-m-d h:i:s', strtotime(Task::where('story_id', $id)->min('date') . ' - ' . $durations[0] . 'days'));
            }
            foreach ($tasks as $key => $taskData) {
                $days += $durations[$key];
                // if ($taskData['status'] == 2) {
                    $task = Task::find($taskData['id']);
                    $task->duration = $durations[$key];
                    if ($task['title'] == 'Submissions 1') {
                        $time = true;
                        $task->date =  date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+10 . ' minutes'));
                    } 
                    else {
                        if($time)
                        {
                            $task->date =  date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+20 . ' minutes'));
                        }
                        else
                        {
                            $task->date = date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days'. $key.' minutes'));
                        }
                    }
                    $task->update();
                    if ($taskData['type'] === 'Automate' && $taskData['status'] == 2) {
                        if (Job::where('task_id', $taskData['id'])->exists()) {
                            $job = Job::where('task_id', $taskData['id'])->first();
                            $jobData = Job::find($job['id']);
                            if ($task['title'] == 'Submissions 1') {
                                $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+10 . 'minutes')));
                            } else {
                                if($time)
                                {
                                    $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days'. $key+20 . 'minutes')));
                                }
                                else
                                {
                                    $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days'. $key.' minutes')));
                                }
                            }
                            $jobData->update();
                        }
                        else
                        {
                            TaskAutomate::dispatch();
                            $job = Job::orderBy('created_at', 'desc')->first();
                            $jobData = Job::find($job->id);
                            $jobData->available_at = strtotime($task->date);
                            $jobData->task_id = $task->id;
                            $jobData->update();
                        }
                    }
                // }
            }
        }
    }
    public function changeTaskOrder(Request $request)
    {
        $time = false;
        $unmutableTasks = Task::where('story_id', $request->id)->orderBy('date', 'ASC')->get();
        $changedTasks = $request->changeOrder;
        $durations = collect($changedTasks)->pluck('duration')->toArray();
        $days = 0;
        if (count($durations) > 0) {
            $date = date('Y-m-d h:i:s', strtotime(Task::where('story_id', $request->id)->min('date') . ' - ' . $durations[0] . 'days'));
            foreach ($changedTasks as $key => $taskData) {
                $days += $durations[$key];
                // if ($taskData['status'] != 1) {
                    $task = Task::find($taskData['id']);
                    $task->duration = $durations[$key];
                    if ($task->title == 'Submissions 1') {
                        $time = true;
                        $task->date = date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+10 . ' minutes'));
                    } else {
                        if($time)
                        {
                            $task->date = date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+20 . ' minutes'));
                        }
                        else
                        {
                            $task->date = date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+1 . ' minutes'));
                        }
                    }
                    $task->update();
                    if ($task->type == 'Automate' && $taskData['status'] != 1) {
                        if (Job::where('task_id', $taskData['id'])->exists()) {
                            $job = Job::where('task_id', $taskData['id'])->first();
                            $jobData = Job::find($job['id']);
                            if ($task->title == 'Submissions 1') {
                                $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days' . $key+10 . ' minutes')));
                            } else {
                                if($time)
                                {
                                    $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days'. $key+20 . ' minutes')));
                                }
                                else
                                {
                                    $jobData->available_at = strtotime(date('Y-m-d h:i:s', strtotime($date . ' + ' . $days . 'days'. $key+1 . ' minutes')));
                                }
                            }
                            $jobData->update();
                        }
                        else
                        {
                            TaskAutomate::dispatch();
                            $job = Job::orderBy('created_at', 'desc')->first();
                            $jobData = Job::find($job->id);
                            $jobData->available_at = strtotime($task->date);
                            $jobData->task_id = $task->id;
                            $jobData->update();
                        }
                    }
                // }
            }
        }
        return response()->json('Task Updated Successfully');
    }
    public function getDefaultTask()
    {
        $tasks = DefaultTask::orderBy('task_sequence', 'ASC')->get();
        foreach ($tasks as $key => $task) {
            $tasks[$key]['emailTemplate'] = EmailTemplates::find($task->email_template_id);
        }
        return response()->json($tasks);
    }
    public function changeDefaultTaskOrder(Request $request)
    {
        $tasks = $request->tasks;
        $i = 1;
        foreach ($tasks as $data) {
            $task = DefaultTask::find($data['id']);
            $task->task_sequence = $i++;
            $task->update();
        }
        return response()->json('Task Updated Successfully');
    }
    public function editDefaultTask(Request $request)
    {
        $task = DefaultTask::find($request->id);
        return response()->json($task);
    }
    public function updateDefaultTask(Request $request)
    {
        $task = DefaultTask::find($request->id);
        $task->title = $request->title;
        $task->email_template_id = $request->email_template_id;
        $task->type = $request->type;
        $task->duration = $request->duration;
        $task->update();
        return response()->json('Default Task Updated Successfully');
    }
    public function runTask(Request $request)
    {
        $task = Task::find($request->id);
        $story = Story::find($task['story_id']);
        $user = User::find($story['user_id']);
        $lastTask = Task::where('date', '<', $task['date'])->where('story_id', $task['story_id'])->orderBy('id', 'desc')->first();
        if(($lastTask && $lastTask['status'] == 1) || empty($lastTask))
        {
            if ($task['email_template_id'] !== null) {
                $totalStorySubmission = JournalStorySubmissions::where('story_id', $story['id'])->get();
                $emailTemplate = EmailTemplates::find($task['email_template_id']);
                $tableHTML = '';
                $tableHTML .= '<table border=""><tr><th>Match</th><th>Journal</th><th>Sub. Date</th><th>Status</th><th>Reply Date</th></tr>';
                if (count($totalStorySubmission)>0) {
                    foreach ($totalStorySubmission as $submission) {
                        $matchPercent = $submission['matchPercent'];
                        $jrnlName = Journals::find($submission['journal_id'])->jrnlName;
                        $subDate = date("m-d-Y", strtotime($submission['created_at']));
                        if ($submission['status'] == 1) {
                            $status = 'Active';
                        } else if ($submission['status'] == 2) {
                            $status = 'Withdrawn';
                        } else if ($submission['status'] == 3) {
                            $status = 'Rejected';
                        } else if ($submission['status'] == 4) {
                            $status = 'Accepted';
                        } else {
                            $status = 'Pending';
                        }
                        $createdAt = Carbon::parse($submission['created_at']);
                        $updatedAt = Carbon::parse($submission['updated_at']);
    
                        if ($createdAt->equalTo($updatedAt)) {
                            $replyDate = '';
                        } else if ($status == 'Pending') {
                            $replyDate = '';
                        } else {
                            $replyDate = date("m-d-Y", strtotime($submission['updated_at']));
                        }
                        $tableHTML .= "<tr><td>$matchPercent%</td><td>$jrnlName</td><td>$subDate</td><td>$status</td><td>$replyDate</td></tr>";
                    }
                } else {
                    $tableHTML .= '<tr><td colspan="5" style="text-align:center;">No Data</td></tr>';
                }
                $tableHTML .= '</table>';
                $emailTemplate['templateDesign'] = str_replace("{client's_first_name}", $user['firstName'], $emailTemplate['templateDesign']);
                $emailTemplate['templateDesign'] = str_replace('{story_title}', $story['storyTitle'], $emailTemplate['templateDesign']);
                $emailTemplate['templateDesign'] = str_replace('{submission_result}', $tableHTML, $emailTemplate['templateDesign']);

                if($user->email)
                {
                    Mail::to($user->email)->bcc('info@submititnow.com')->send(new \App\Mail\SendEmail($user, $story, $task, $emailTemplate['title'], $emailTemplate['templateDesign']));
                }
                    // if ($user->sharedEmail) {
                //     Mail::to($user->sharedEmail)->send(new \App\Mail\SendEmail($user, $story, $task, $emailTemplate['title'], $emailTemplate['templateDesign']));
                // }
            }
            $duration = 0;
            if($lastTask)
            {
                $diff = date_diff(date_create($lastTask['date']), date_create(date('Y-m-d h:i:s')));
                $duration = (int)$diff->format("%a");
                $task['duration'] = $duration;
                $task->update();
                $this->updateAllTask($task['story_id']);
            }
            else
            {
                $diff = date_diff(date_create($task['date']), date_create(date('Y-m-d h:i:s')));
                $task['duration'] = (int)$diff->format("%a");
                $task->update();
                $this->updateAllTask($task['story_id'], date('Y-m-d h:i:s', strtotime(Task::where('story_id', $task['story_id'])->min('date'))));
            }
            $task = Task::find($request->id);
            $task['date'] = date("Y-m-d h:i:s");
            $task['status']=1;
            $task->update();
            return response()->json("1");
        }
        return response()->json("0");
    } 
}