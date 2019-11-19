<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use Storage;
use Carbon\Carbon;

class AdminController extends Controller

{

    public function __construct()
    {

        $this->middleware('admin');
    }


    public function home()
    {

       

        $users = DB::table('users')->count() - 1;

        $projects = DB::table('projects')->count();

        $totalRequests = DB::table('stats')->select(
            DB::raw('sum(stats.count) as total_requests')
        )->get();
        $top = $totalRequests[0];
        $totalRequests = $top->total_requests;
        $months = DB::table('stats')->select(['month' ,'year'])
            ->groupBy(['month', 'year'])->get();

        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');

        $platformStats = $this->getPlatformStats();

        return view('admin.admin-home', compact([
            'totalRequests', 'totalRequests',
            'users', 'projects', 'platformStats',
            'months', 'currentYear', 'currentMonth'
        ]));
    }

    

    public function getPlatformStats ()
    {

        $dataSize = DB::table('data')->select(
            DB::raw('sum(data.size) as data_size')
        )->get();
        $dataSize = ($dataSize[0])->data_size;
        $assetsSize = DB::table('assets')->select(
            DB::raw('sum(assets.size) as assets_size')
        )->get();
        

        $assetsSize = ($assetsSize[0])->assets_size;

        return [$dataSize, $assetsSize];
    }


    public function showLogs()
    {

        $logs = DB::table('logs')->orderBy('id', 'desc')->get();

        return view('admin.admin_logs', compact('logs'));
    }

    public function showActiveUsers()
    {

        $users = DB::table('users')->where('status', 'Active')->get();

        return view('admin.active_users', compact('users'));
    }

    public function showSuspendedUsers()
    {

        $users = DB::table('users')->where('status', 'Suspended')->get();

        return view('admin.suspended-users', compact('users'));
    }

    public function showNewUser()
    {

        return view('admin.new-user');
    }

    public function showActiveUser($id)
    {


        $user = User::find($id);
        $plan_id = $user->plan_id;
        $plan = Plan::find($plan_id);
        

        $projectCount = DB::table('projects')->where('user_id', $id)->count();
        $userAccount = new UserAccountsController();
        $usage = $userAccount->getAccountResourceUsage($id);

        return view('admin.active_user', compact(['user', 'projectCount', 'usage', 'plan']));
    }

    public function showSuspendedUser($id)
    {

        $user = User::find($id);

        $totalRequests = DB::table('logs')->where('account_id', $id)->count();

        return view('admin.suspended-user', compact(['user', 'totalRequests']));
    }


    public function deleteUserAccount(Request $request)
    {
        //issue: this function is smelly

        if ($request->delete_user != 1)
            //just for verification
            return 'incomplete request';

        $userId = $request->user_id;

        $user = User::find($userId);

        $tokens =  $user->tokens;

        foreach ($tokens as $token) {
            $token->revoke();
        }

        User::find($userId)->delete(); // issue: revoke the token

        return redirect('/admin/dashboard')->withErrors([
            'account_deleted'
        ]); //issue: create a message


    }

    public function suspendUserAccount(Request $request)
    {

        if ($request->suspend_user != 1)
            return "incomplete request";

        $userId = $request->user_id;

        $user = User::find($userId);
        $user->status = 'Suspended';
        $user->last_suspended = Carbon::now()->toDateTimeString();
        $user->save();

        return redirect('/admin/users/suspended/' . $userId);
    }

    public function activateUserAccount(Request $request)
    {

        if ($request->activate_user != 1)
            return "incomplete request";

        $userId = $request->user_id;

        $user = User::find($userId);
        $user->status = 'Active';
        $user->last_activated = Carbon::now()->toDateTimeString();
        $user->save();

        return redirect('/admin/users/active/' . $userId);
    }
}
