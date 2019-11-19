<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use Auth;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
    //

    public function showPlans () {

        $plans = \App\Plan::all();
        
        $subscribers = [];
        foreach ($plans as $plan) {
            $count = DB::table('users')->where('plan_id', $plan->id)
            ->count();
            $subscribers[$plan->id] = $count;
        } 
        

        return view ('admin.plans_home', compact(['plans', 'subscribers']));
    }

    public function showEditPage ($id) {
        
        $plan = Plan::find($id);
        return view ('admin.edit_plan', compact('plan'));
    }

    public function createPlan (Request $request) {

        $request->validate([
            'name' => 'required',
            'projects' => 'required',
            'storage_limit' => 'required',
            'store_limit' => 'required',
        ]);

        \App\Plan::create([
            'name' => $request->name,
            'projects' => $request->projects,
            'storage_limit' => $request->storage_limit,
            'store_limit' => $request->store_limit
        ]);

        return back();  

    }

    public function change (Request $request, $id) {

        $plan = Plan::find($id);
        $plan->name = $request->name;
        $plan->projects = $request->projects;
        $plan->storage_limit = $request->storage_limit;
        $plan->store_limit = $request->store_limit;

        $plan->save();

        return redirect('/admin/plans');
    }

    public function showUserPlan () {

        $plans = Plan::all();
        //we need to spit out the user info

        return view ('user.plans', compact('plans'));
        
    }

    function deletePlan (Request $request) {

        $request->validate([
            'plan_id' => 'required'
        ]);

        $plan = Plan::find($request->plan_id);
        $plan->delete();

        return back();

    }

    function migrateToPlan (Request $request) {

        $request->validate ([
            'plan_id' => 'required'
        ]);

        $user = Auth::user();
        $user->plan_id = $request->plan_id;

        $user->save();

        return back()->withErrors(['migrated']);
    }
}
