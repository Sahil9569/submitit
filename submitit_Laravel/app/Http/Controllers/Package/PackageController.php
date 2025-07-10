<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Packages, SchedulePayment, Story, User};

class PackageController extends Controller
{
    public function getPackages()
    {
        $packages = Packages::all();
        return response()->json($packages);
    }
    public function getPendingPayments()
    {
        $pending = SchedulePayment::where('status', "Pending")->orderBy('id', 'DESC')->get()->toArray();
        foreach ($pending as $key => $service) {
            $story = Story::where('id', $service['story_id'])->first();
            $pending[$key]['story_id'] = $story;
            $packageId = explode(',', $service['packages']);
            $pending[$key]['user_id'] = User::find($story['user_id']);
            $pending[$key]['packages'] = Packages::whereIn('stripe_package_id', $packageId)->get();
        }
        return response()->json($pending);
    }
}
