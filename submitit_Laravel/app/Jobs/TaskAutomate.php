<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Http\Controllers\Story\TaskController;
use App\Models\{Job, EmailTemplates, Story, User, Task, JournalStorySubmissions, Journals};
use Mail;

class TaskAutomate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $jobId = $this->job->getJobId();
            $job = Job::where('id', $jobId)->first();
            $taskId = $job->task_id;
            $request = new Request();
            $request->merge(['id' => $taskId]);
            $task = new TaskController();
            $task->runTask($request);
        } catch (\Exception $e) { 
            $this->release(300);
        }
    }
}
