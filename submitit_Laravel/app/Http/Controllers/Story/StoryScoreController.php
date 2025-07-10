<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\StoryScore;
use Illuminate\Http\Request;

class StoryScoreController extends Controller
{
    public function storeStoryScore(Request $request)
    {
        $storyScore = StoryScore::updateOrCreate(['story_id' => $request->story_id], $request->except(['id']));
        return response()->json('Score Added Successfully');
    }
}
