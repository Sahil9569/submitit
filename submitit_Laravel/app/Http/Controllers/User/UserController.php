<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\{User, Story, Task, Journals};

class UserController extends Controller
{
    public function getAuthenticatedUser(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        return response()->json($user);
    }
    public function getSingleClient(Request $request)
    {
        $client = User::find($request->id);
        $clientStory = Story::where('user_id', $request->id)->get();
        foreach ($clientStory as $key => $story) {
            $clientStory[$key]['lastTask'] = Task::where('story_id', $story->id)->where('status', 1)->orderBy('updated_at', 'DESC')->first();
            $clientStory[$key]['nextTask'] = Task::where('story_id', $story->id)->where('status', 2)->orderBy('date', 'ASC')->first();
        }
        return response()->json([
            "client" => $client,
            "clientStories" => $clientStory,
        ]);
    }

    public function editClient(Request $request)
    {
        $client = User::find($request->id);
        $client['doNotSubmitList'] = explode(',', $client['doNotSubmitList']);
        $clientPassword = User::find($request->id)->password;
        $journals =  $journals = Journals::orderBy('id', 'DESC')->get();
        return response()->json(['client'=>$client ,'clientPassword'=> $clientPassword, 'journals'=>$journals]);
    }

    public function updateClient(Request $request)
    {
        $client = User::find($request->id);
        $client->firstName = $request->firstName;
        $client->lastName = $request->lastName;
        $client->penName = $request->penName;
        if(count((array)$request->doNotSubmitList)>=0)
        {
            $client->doNotSubmitList = implode(',', (array)$request->doNotSubmitList);
        }
        $client->street = $request->street;
        $client->city = $request->city;
        $client->state = $request->state;
        $client->zipCode = $request->zipCode;
        $client->country = $request->country;
        $client->referral = $request->referral;
        $client->date =  substr($request->date, 0, 10);
        $client->phone = $request->phone;
        $client->sharedEmail = $request->sharedEmail;
        $client->status = 1;
        $client->email = $request->email;
        $client->sharedEmailPassword = $request->sharedEmailPassword;
        $client->notes = $request->notes;
        $client->bio = $request->bio;
        $client->update();
        return response()->json(['message'=>'Update Successfully!', 'user'=>$client]
        );
    }
}
