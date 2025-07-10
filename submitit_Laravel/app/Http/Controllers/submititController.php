<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Story, StoryScore, Journals, JournalStory, Task, JournalStorySubmissions, Packages, EmailTemplates};
use Illuminate\Support\Facades\{Auth, DB, Mail, Hash, Validator};
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class submititController extends Controller
{

    public function registrationClient(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'email' => 'unique:users'
        ]);
        if ($credential->fails()) {
            return response()->json([
                'errors' => $credential->errors()
            ], 422);
        }
        $user_data = new User();
        $user_data->firstName = $request->firstName;
        $user_data->lastName = $request->lastName;
        $user_data->penName = $request->penName;
        $user_data->doNotSubmitList = implode(',', $request->doNotSubmitList);
        $user_data->street = $request->street;
        $user_data->city = $request->city;
        $user_data->state = $request->state;
        $user_data->country = 'USA';
        $user_data->zipCode = $request->zipCode;
        $user_data->referral = $request->referral;
        $user_data->date =  substr($request->date, 0, 10);
        $user_data->phone = $request->phone;
        $user_data->sharedEmail = $request->sharedEmail;
        $user_data->email = $request->email;
        $user_data->sharedEmailPassword = $request->sharedEmailPassword;
        $user_data->notes = $request->notes;
        $user_data->bio = $request->bio;
        $user_data->save();
        // if($request->password!=null)
        // {
        //     dispatch(new \App\Jobs\PaymentJob($user_data->email, $user_data, $password, 1));
        // }
        return response()->json([
            'message' => 'Registration Successfull!',
            'user' => $user_data
        ]);
    }

    public function login(Request $request)
    {
        $Credential = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($Credential->fails()) {
            return response()->json([
                'errors' => $Credential->errors()
            ], 422);
        }
        $login_data = array(
            'email' => $request->email,
            'password' => $request->password
        );
        if (Auth::attempt($login_data)) {
            if (Auth::user()->userType === 'admin') {
                return response()->json(
                    auth()->user()
                );
            } else {
                return response()->json(
                    auth()->user()
                );
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Invalid email and password !!'
            ]);
        }
    }

    // logout

    public function logout()
    {
        Auth::logout();
        return response()->json('logout successful');
    }


    // ---Change Password
    public function changePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
            ], 422);
        }
        User::where('id', $request->id)->update(['password' => Hash::make($request->new_password)]);
        return response()->json([
            'message' => 'Password is Updated !!'
        ]);
    }



    // Send Mail For Forgot Password 

    public function passwordMail(Request $request)
    {
        $credential = Validator::make($request->all(), [
            'email' => 'exists:users'
        ]);
        if ($credential->fails()) {
            return response()->json([
                'errors' => $credential->errors()
            ], 422);
        }

        $token = Str::random(64);
        DB::table('password_resets')->updateOrInsert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        Mail::send('Mail', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password ?');
        });
        return response()->json([
            'message' => 'Mail send successfully!!'
        ]);
    }

    // forgot-password-reset


    public function forgotPasswordReset(Request $request)
    {

        $credential = Validator::make($request->all(), [
            'email' => 'exists:users',
            'new_password' => 'required',
            'confirm_password' => 'same:new_password'
        ]);
        if ($credential->fails()) {
            return response()->json([
                'errors' => $credential->errors()
            ], 422);
        }

        $update_data = DB::table('password_resets')->where([
            'token' => $request->token
        ])->first();
        if (!$update_data) {
            return response()->json([
                "error" => true,
                'message' => 'Invalid token !!'
            ]);
        }
        $user = DB::table('password_resets')->where(['token' =>  $request->token])->first();
        User::where('email', $user->email)->update(['password' => Hash::make($request->new_password)]);
        DB::table('password_resets')->where(['email' => $user->email])->delete();
        return response()->json([
            'message' => 'Password is Updated !!'
        ]);
    }

    public function getEmail(Request $request)
    {
        $email = DB::table('password_resets')->where([
            'token' => $request->token
        ])->first();
        return response()->json($email);
    }
    public function getClients()
    {
        $client = User::with('stories')->where('userType', 'client')->orderBy('id', 'DESC')->get();
        return response()->json($client);
    }
    public function getAllData()
    {
        $users = User::where('userType', 'client')->orderBy('id', 'DESC')->get();
        $stories = Story::orderBy('id', 'DESC')->get();
        $storyScores = StoryScore::orderBy('id', 'DESC')->get();
        $journals = Journals::orderBy('id', 'DESC')->get();
        $journalStories = JournalStory::orderBy('id', 'DESC')->get();
        $tasks = Task::orderBy('id', 'DESC')->get();
        $storySubmission = JournalStorySubmissions::orderBy('id', 'DESC')->get();
        $emailTemplates = EmailTemplates::orderBy('id', 'DESC')->get();
        return response()->json(['users'=>$users, 'stories'=>$stories,'journals'=>$journals, 'journalStories'=>$journalStories, 'storyScores'=>$storyScores, 'tasks'=>$tasks, 'storySubmission'=>$storySubmission, 'emailTemplates'=>$emailTemplates]);
    }
    public function getData()
    {
        $packages = Packages::all();
        return response()->json(['packages'=>$packages]);
    }
    public function getStoryJournalData()
    {
        $addMoreJournals = DB::table('add_more_journals')->get();
        return response()->json(['addMoreJournals'=>$addMoreJournals]);
    }
}   
