<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\ProjectsController;
use App\Plan;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Hash;
use Carbon\Carbon;
use DB;

class UserAccountsController extends Controller
{

    var $success = null; // this variable keeps all custom success messages


    public function __construct () {

        $this->middleware('auth');

    }

    public function showDashboard () {

        $userId = Auth::user()->id;

        $username = Auth::user()->name;

        $projects = DB::table('projects')->select('name', 'id', 'created_at')->where(
            'user_id', Auth::user()->id
        )->get();

        $projectsController = new ProjectsController();
        $usage = [];
        foreach ($projects as $project) {
            $usage[$project->id] = $projectsController->getProjectChartDetails($project->id, 'self');
        }

        $planUsage = $this->getPlanBrief();

        return view('user.user-home', compact(
            ['username', 'projects', 'usage',
            'planUsage']
        ));
    }

    public function showAccountInfo () {

        $userId = Auth::user()->id;

        $userInfos =  DB::table('users')->where('id', $userId)->select(
            ['name', 'email','created_at','updated_at',
            'status', 'last_suspended', 'total_requests_served']
            )->get();

        $userInfo = $userInfos[0];
        $userInfo->created_at=  Carbon::parse($userInfo->created_at)->toDayDateTimeString();
        $userInfo->updated_at =  Carbon::parse($userInfo->updated_at)->toDayDateTimeString();

        $totalRequestCount = DB::table('logs')->where([
            'account_id' => $userId,
        ])->count();

        return view('user.account_info', compact(['userInfo', 'totalRequestCount']));
    }

    public function showAuthPage () {

        $user = Auth::user();
        $requestInfo =  (object)[];
        $requestInfo->auth_token = $user->auth_token;
        $requestInfo->date_issued = Carbon::parse($user->date_issued)->toDayDateTimeString();
        $requestInfo->date_changed = Carbon::parse($user->date_changed)->toDayDateTimeString();
        $requestInfo->expiry_date = Carbon::parse($user->expiry_date)->toDayDateTimeString();

        return view('user.request_auth', compact('requestInfo'));
    }

    public function showChangeProfilePage () {

        $success = $faults = null;

        $user = Auth::user();

        return view('user.change_profile', compact(['user', 'success', 'faults']));
    }

    public function showLogs () {

        $logs = DB::table('logs')
            ->where('account_id', Auth::user()->id)
            ->orderBy('id', 'desc')->get();

        return view('user.user_logs', compact('logs'));
    }

    public function getAccountResourceUsage ($account_id) {


        $dataAll = DB::table('data')->select(
            DB::raw('sum(data.size) as data_all')
        )->where('user_id', $account_id)->get();
        

        $assetAll = DB::table('assets')->select(
            DB::raw('sum(assets.size) as assets_all')
        )->where('user_id', $account_id)->get();

        $assetsAll  = ($assetAll[0])->assets_all;
        $dataAll  = ($dataAll[0])->data_all;

        return ['store' => $dataAll,
        'storage' => $assetsAll
        ];

    }

    public function getPlanBrief () {

        $resources = $this->getAccountResourceUsage(Auth::user()->id);
        $projects = Auth::user()->projects()->count();
        $plan_id = Auth::user()->plan_id;
        $plan = Plan::find($plan_id);

        return [
            'storage' => $resources['storage'],
            'store' => $resources['store'],
            'projects'=> $projects,
            'plan_name' => $plan->name,
            'plan_storage'=> $plan->storage_limit,
            'plan_store' => $plan->store_limit,
            'plan_projects' => $plan->projects,
        ];        

    }

    public function isEligibleTo ($attribute) {

        $resources = $this->getPlanBrief();
        if ($attribute == 'store'){
            if ($resources['store'] >= ($resources['plan_store']*1024*1024))
                return false;
            else return true;
        
        } else if ($attribute == 'storage') {
            if ($resources['storage'] >= $resources['plan_storage']*1024*1024*1024)
                return false;
            else return true;
        
        } else if ($attribute == 'project') {
            if ($resources['projects'] == $resources ['plan_projects'] )
                return false;
            else return true;
        }

        return false;

    }
        


}
