<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Journals, JournalScore, JournalStorySubmissions, StoryScore, Story, JournalStory};
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function addJournal(Request $request)
    {
        $files = [];
        $journal = new Journals;
        $journal->jrnlName = $request->jrnlName;
        $journal->rating = $request->rating;
        $journal->date = $request->date;
        $journal->fictionSubmission = $request->fictionSubmission == 1 ? 1 : 0;
        $journal->nonFictionSubmission = $request->nonFictionSubmission == 1 ? 1 : 0;
        $journal->fictionOnly = $request->fictionOnly == 1 ? 1 : 0;
        $journal->nonFictionOnly = $request->nonFictionOnly == 1 ? 1 : 0;
        $journal->onlineSubmission = $request->onlineSubmission == 1 ? 1 : 0;
        $journal->simultaneousSubmission = $request->simultaneousSubmission == 1 ? 1 : 0;
        $journal->defunct = $request->defunct == 1 ? 1 : 0;
        $journal->notIncluded = $request->notIncluded == 1 ? 1 : 0;
        $journal->possibleThemes = $request->possibleThemes == 1 ? 1 : 0;
        $journal->flash = $request->flash;
        $journal->yearOfInception = $request->yearOfInception;
        $journal->acceptance = $request->acceptance / 100;
        $journal->volume = $request->volume;
        $journal->maximumLength = $request->maximumLength;
        $journal->minimumLength = $request->minimumLength;
        $journal->permanentThemes = $request->permanentThemes;
        $journal->notesAboutJournal = $request->notesAboutJournal;
        if (($request->acceptance / 100) > 0.1) {
            $journal->acceptancePercent = 5;
        } else if (($request->acceptance / 100) > 0.05 && ($request->acceptance / 100) <= 0.1) {
            $journal->acceptancePercent = 4;
        } else if (($request->acceptance / 100) > 0.03 && ($request->acceptance / 100) <= 0.05) {
            $journal->acceptancePercent = 3;
        } else if (($request->acceptance / 100) > 0.03 && ($request->acceptance / 100) < 0.01) {
            $journal->acceptancePercent = 2;
        } else if (($request->acceptance / 100) < 0.01) {
            $journal->acceptancePercent = 1;
        }
        if ($request->volume > 200) {
            $journal->submissionVolume = 5;
        } else if ($request->volume > 100  && $request->volume <= 200) {
            $journal->submissionVolume = 4;
        } else if ($request->volume > 50  && $request->volume <= 100) {
            $journal->submissionVolume = 3;
        } else if ($request->volume > 10  && $request->volume <= 50) {
            $journal->submissionVolume = 2;
        } else {
            $journal->submissionVolume = 1;
        }
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '01submitit10' . $file->getClientOriginalName();
                $file->move(public_path() . '/files', $fileName);
                array_push($files, $fileName);
            }
        }
        $journal->files = implode(',', $files);
        $journal->theme = $request->theme;
        $journal->submissionPlatform = $request->submissionPlatform;
        $journal->submissionGuidelines = $request->submissionGuidelines;
        $journal->submissionCost = $request->submissionCost;
        $journal->save();
        return response()->json($journal);
    }
    public function getJournals()
    {
        $journals = Journals::orderBy('id', 'DESC')->get();
        return response()->json($journals);
    }
    public function getJournalsWithScore(Request $request)
    {
        if ($request->id != null) {
            $journals = Journals::where('id', $request->id)->get();
        } 
        else {
            $story = Story::find($request->storyId);
            if ($story->storyGenre == 'fiction') {
                $journals = Journals::where('fictionSubmission', 1)->where('minimumLength', '<=', (int)$story->wordCount)->where('maximumLength', '>=', (int)$story->wordCount)->where('nonFictionOnly', 0)->where('defunct', 0)->where('notIncluded', 0)->get();
            } else {
                $journals = Journals::where('nonFictionSubmission', 1)->where('minimumLength', '<=', (int)$story->wordCount)->where('maximumLength', '>=', (int)$story->wordCount)->where('fictionOnly', 0)->where('defunct', 0)->where('notIncluded', 0)->get();
            }
        }
        foreach ($journals as $key =>  $journal) {
            $journalScore = new JournalScoreVariable();
            $quality = $stylized = $difficulty = $topical = $experimental = $humor = $strange = $dark = $international = $margins = $historical = $long = $short = $anthologies = $dummyStory = 0;
            $journalStories = JournalStory::where('journal_id', $journal->id)->get();
            if (count($journalStories) > 0) {
                foreach ($journalStories as $story) {
                    $quality = $story->quality + $quality;
                    $stylized = $story->stylized + $stylized;
                    $difficulty = $story->difficulty + $difficulty;
                    $topical = $story->topical + $topical;
                    $experimental = $story->experimental + $experimental;
                    $humor = $story->humor + $humor;
                    $strange = $story->strange + $strange;
                    $dark = $story->dark + $dark;
                    $international = $story->international + $international;
                    $margins = $story->margins + $margins;
                    $historical = $story->historical + $historical;
                    $long = $story->long + $long;
                    $short = $story->short + $short;
                    $anthologies = $anthologies + (int)($story->isAnthologies == 'true' ? 1 : 0);
                    $dummyStory = $dummyStory + (int)($story->dummyStory == 'true' ? 1 : 0);
                }
                $journalScore->quality = number_format($quality / count($journalStories), 1, '.', '');
                $journalScore->stylized =  number_format($stylized / count($journalStories), 1, '.', '');
                $journalScore->difficulty =  number_format($difficulty / count($journalStories), 1, '.', '');
                $journalScore->topical =  number_format($topical / count($journalStories), 1, '.', '');
                $journalScore->experimental =  number_format($experimental / count($journalStories), 1, '.', '');
                $journalScore->humor =  number_format($humor / count($journalStories), 1, '.', '');
                $journalScore->strange =  number_format($strange / count($journalStories), 1, '.', '');
                $journalScore->dark =  number_format($dark / count($journalStories), 1, '.', '');
                $journalScore->international =  number_format($international / count($journalStories), 1, '.', '');
                $journalScore->margins =  number_format($margins / count($journalStories), 1, '.', '');
                $journalScore->historical =  number_format($historical / count($journalStories), 1, '.', '');
                $journalScore->long =  number_format($long / count($journalStories), 1, '.', '');
                $journalScore->short =  number_format($short / count($journalStories), 1, '.', '');
                $journalScore->stories = count($journalStories);
                $journalScore->anthologies = $anthologies;
                $journalScore->dummyStory = $dummyStory;
            }
            $journals[$key]->journal_scores = $journalScore;
        }
        return response()->json($journals);
    }
    public function deleteJournal(Request $request)
    {
        if (JournalStory::where('journal_id', $request->id)->exists()) {
            JournalStory::where('journal_id', $request->id)->delete();
        }
        if (JournalStorySubmissions::where('journal_id', $request->id)->exists()) {
            JournalStorySubmissions::where('journal_id', $request->id)->delete();
        }
        $journal = Journals::find($request->id);
        $journal->delete();
        return response()->json('Journal Deleted Successfully');
    }
    public function getSingleJournal(Request $request)
    {
        $journal = Journals::find($request->id);
        return response()->json(['journal' => $journal]);
    }
    public function getRecentJournals()
    {
        $journals = Journals::orderBy('id', 'DESC')->limit(10)->get();
        return response()->json($journals);
    }

    public function editJournal(Request $request)
    {
        $journal = Journals::find($request->id);
        $journal['dateLastChecked'] = $journal['date'];
        return response()->json($journal);
    }

    public function updateJournal(Request $request)
    {
        $files = [];
        $journal = Journals::find($request->id);
        $journal->jrnlName = $request->jrnlName;
        $journal->rating = $request->rating;
        $journal->date = $request->date;
        $journal->fictionSubmission = $request->fictionSubmission ? 1 : 0;
        $journal->nonFictionSubmission = $request->nonFictionSubmission ? 1 : 0;
        $journal->fictionOnly = $request->fictionOnly ? 1 : 0;
        $journal->nonFictionOnly = $request->nonFictionOnly ? 1 : 0;
        $journal->onlineSubmission = $request->onlineSubmission ? 1 : 0;
        $journal->simultaneousSubmission = $request->simultaneousSubmission ? 1 : 0;
        $journal->defunct = $request->defunct ? 1 : 0;
        $journal->notIncluded = $request->notIncluded ? 1 : 0;
        $journal->possibleThemes = $request->possibleThemes ? 1 : 0;
        $journal->flash = $request->flash;
        $journal->yearOfInception = $request->yearOfInception;
        $journal->acceptance = $request->acceptance / 100;
        $journal->volume = $request->volume;
        $journal->maximumLength = $request->maximumLength;
        $journal->minimumLength = $request->minimumLength;
        $journal->permanentThemes = $request->permanentThemes;
        $journal->notesAboutJournal = $request->notesAboutJournal;
        if (($request->acceptance / 100) > 0.1) {
            $journal->acceptancePercent = 5;
        } else if (($request->acceptance / 100) > 0.05 && ($request->acceptance / 100) <= 0.1) {
            $journal->acceptancePercent = 4;
        } else if (($request->acceptance / 100) > 0.03 && ($request->acceptance / 100) <= 0.05) {
            $journal->acceptancePercent = 3;
        } else if (($request->acceptance / 100) > 0.03 && ($request->acceptance / 100) < 0.01) {
            $journal->acceptancePercent = 2;
        } else if (($request->acceptance / 100) < 0.01) {
            $journal->acceptancePercent = 1;
        }
        if ($request->volume > 200) {
            $journal->submissionVolume = 5;
        } else if ($request->volume > 100  && $request->volume <= 200) {
            $journal->submissionVolume = 4;
        } else if ($request->volume > 50  && $request->volume <= 100) {
            $journal->submissionVolume = 3;
        } else if ($request->volume > 10  && $request->volume <= 50) {
            $journal->submissionVolume = 2;
        } else {
            $journal->submissionVolume = 1;
        }
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
        $request->except('images');
        $journal->files = implode(',', $files);
        $journal->theme = $request->theme;
        $journal->submissionPlatform = $request->submissionPlatform;
        $journal->submissionGuidelines = $request->submissionGuidelines;
        $journal->submissionCost = $request->submissionCost;
        $journal->update();
        return response()->json($journal);
    }
    public function getJounralStories(Request $request)
    {
        $stories = JournalStorySubmissions::where('journal_id', $request->id)->where('status', 1)->get();
        foreach ($stories as $key => $story) {
            $stories[$key]['story'] = Story::find($story->story_id);
        }
        return response()->json($stories);
    }
    public function getAllJournals()
    {
        $scoredJournal = JournalStorySubmissions::where('status', 1)->pluck('journal_id')->toArray();
        $journals = Journals::whereNotIn('id', $scoredJournal)->get();
        foreach ($journals as $key => $journal) {
            $journals[$key]['matchScore'] = '0';
        }
        return response()->json($journals);
    }
    public function addDummyStory(Request $request)
    {
        $journal = Journals::find($request->id);
        $journal->dummyStory = $request->dummyStory;
        $journal->update();
        return response()->json('Dummy Story Added Successfully');
    }
    public function updateAllJournalStatus(Request $request)
    {
        $journals = $request->journals;
        foreach ($journals as $journal) {
            Journals::find($journal['id'])->update(["fictionSubmission" => $journal['fictionSubmission'], "nonFictionSubmission" => $journal['nonFictionSubmission']]);
        }
        return response()->json('Journal Status Updated Successfully');
    }
    public function addMoreJournal(Request $request)
    {
        $journals = $request->all();
        foreach ($journals as $journal) {
            $journalData = DB::table('add_more_journals')->insert(['journal_id' => $journal['id'], 'story_id' => $journal['story_id']]);
            // $journalData = new add_more_journal();
            // $journalData->journal_id = $journal['id'];
            // $journalData->story_id = $journal['story_id'];
            // $journalData->save();
        }
        return response()->json($journals);
    }
    public function removeAddMoreJournal(Request $request)
    {
        DB::table('add_more_journals')->where('journal_id', $request->id)->where('story_id', $request->storyId)->delete();
        $journalData = DB::table('add_more_journals')->where('story_id', $request->storyId)->get();
        if (!empty($journalData)) {
            $journals = [];
            foreach ($journalData as $key => $journal) {
                $journals[$key] = Journals::where('id', $journal->journal_id)->get();
            }
            return response()->json($journals);
        }
    }

    public function getAddMoreJournal(Request $request)
    {
        $journalData = DB::table('add_more_journals')->where('story_id', $request->story_id)->get();
        if (!empty($journalData)) {
            $journals = [];
            foreach ($journalData as $key => $journal) {
                $journals[$key] = Journals::where('id', $journal->journal_id)->get();
            }
            return response()->json($journals);
        }
    }
}
class JournalScoreVariable
{
    public $quality, $stylized, $difficulty, $topical, $experimental, $humor, $strange, $dark, $international, $margins, $historical, $long, $short, $stories, $anthologies, $dummyStory;
    function __construct()
    {
        $this->quality = 0;
        $this->stylized = 0;
        $this->difficulty = 0;
        $this->topical = 0;
        $this->experimental = 0;
        $this->humor = 0;
        $this->strange = 0;
        $this->dark = 0;
        $this->international = 0;
        $this->margins = 0;
        $this->historical = 0;
        $this->long = 0;
        $this->short = 0;
        $this->stories = 0;
        $this->anthologies = 0;
        $this->dummyStory = 0;
    }
}
