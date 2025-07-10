<?php

namespace App\Http\Controllers\JournalStory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JournalStory;

class JournalStoryController extends Controller
{
    public function journalStoryStore(Request $request)
    {
        $journalStory = $request->all();
        JournalStory::create($journalStory);
        return response()->json('Journal Story Saved Successfully');
    }

    public function getJournalStory(Request $request)
    {
        $journalStory = JournalStory::where('journal_id', $request->id)->get();
        return response()->json($journalStory);
    }

    public function editJournalStory(Request $request)
    {
        $journalStory = JournalStory::find($request->id);
        return response()->json($journalStory);
    }

    public function updateJournalStory(Request $request)
    {
        $journalStory = $request->all();
        JournalStory::find($request->id)->update($journalStory);
        return response()->json($journalStory);
    }

    public function deleteJournalStory(Request $request)
    {
        $journalStory = JournalStory::find($request->id);
        $journalStory->delete();
        return response()->json('Journal Story deleted successfully');
    }

}
