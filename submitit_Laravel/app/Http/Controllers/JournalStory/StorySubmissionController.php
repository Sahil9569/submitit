<?php

namespace App\Http\Controllers\JournalStory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{JournalStorySubmissions, Journals, Story};

class StorySubmissionController extends Controller
{
    public function storySubmission(Request $request)
    {
        $submission = $request->all();
        $submission['user_id'] = Story::find($request->story_id)->user_id;
        if(JournalStorySubmissions::where('story_id', $request->story_id)->where('journal_id', $request->journal_id)->exists())
        {
            $submission['round'] = JournalStorySubmissions::where('story_id', $request->story_id)->where('journal_id', $request->journal_id)->latest()->first()->round + 1;
        }
        $storySubmission = JournalStorySubmissions::create($submission);
        return response()->json('Submission Request Sent Successfully');
    }
    public function getStorySubmissionResult(Request $request)
    {
        $result = JournalStorySubmissions::where('story_id', $request->id)->get();
        foreach($result as $key => $journal)
        {
            $result[$key]->journal = Journals::find($journal->journal_id);
        }
        return response()->json($result);
    }
    public function getSubmissionResult()
    {
        $result = JournalStorySubmissions::where('status', '!=', 0)->get();
        foreach($result as $key => $data)
        {
            $result[$key]->journal = Journals::find($data->journal_id);
            $result[$key]->story = Story::find($data->story_id);
        }
        return response()->json($result);
    }
    public function storyInReview()
    {
        $result = JournalStorySubmissions::where('status', 0)->get();
        return response()->json($result);
    }
    public function getClientStorySubmissionResult(Request $request)
    {
        $result = JournalStorySubmissions::where('user_id', $request->id)->get();
        foreach($result as $key => $data)
        {
            $result[$key]->journal = Journals::find($data->journal_id);
            $result[$key]->story = Story::find($data->story_id);
        }
        return response()->json($result);
    }
    public function getStoryStatus(Request $request)
    {
        $result = [];
        $result['pending'] = count(JournalStorySubmissions::where('user_id', $request->user_id)->where('status', 0)->get());
        $result['accepted'] = count(JournalStorySubmissions::where('user_id', $request->user_id)->where('status', 1)->get());
        $result['withdrawn'] = count(JournalStorySubmissions::where('user_id', $request->user_id)->where('status', 2)->get());
        $result['rejected'] = count(JournalStorySubmissions::where('user_id', $request->user_id)->where('status', 3)->get());
        $result['active'] = count(JournalStorySubmissions::where('user_id', $request->user_id)->where('status', 4)->get());
        return response()->json($result);
    }
    public function editSubmissionData(Request $request)
    {
        $storySubmission = JournalStorySubmissions::find($request->id);
        $storySubmission['date'] = date("m-d-Y", strtotime($storySubmission['updated_at']));
        return response()->json($storySubmission);
    }
    public function updateSubmissionData(Request $request)
    {
        $storySubmission = JournalStorySubmissions::find($request->id);
        $storySubmission->update([
            'status' => $request->status,
            'created_at' => new \DateTime($request->createdDate),
            'updated_at' => new \DateTime($request->date),
        ], ['forceUpdate' => true]);
        return response()->json('Story Submission Status Updated Successfully');
    }
}
