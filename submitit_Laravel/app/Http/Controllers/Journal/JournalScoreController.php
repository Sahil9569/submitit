<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JournalScore;

class JournalScoreController extends Controller
{
    public function storeJournalScore(Request $request)
    {
        $journalScore = JournalScore::updateOrCreate(['journal_id' => $request->journal_id], $request->all());
        return response()->json('Score Stored Successfully');
    }
}
