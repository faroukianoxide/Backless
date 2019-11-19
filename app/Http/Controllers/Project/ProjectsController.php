<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserAccountsController;
use App\Plan;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use DB;
use Auth;
use Carbon\Carbon;

class ProjectsController extends Controller
{
    public function __construct () {

        $this->middleware('auth');

    }

    public function showAllProjects () {

       $projects = DB::table('projects')->select('name', 'id', 'created_at')->where(
            'user_id', Auth::user()->id
        )->get();

        return view('user.develop.projects.all_projects', compact('projects'));
    }

    public function showSettingsPage ($project_id) {
        $result = DB::table('projects')->select('name')
         ->where('id', $project_id)->get();
        $name = ($result[0])->name; 

        return view ('user.develop.projects.settings', compact(['project_id', 'name']));

    }

    public function createNewProject (Request $request){

        $userAccount = new UserAccountsController();

        if (!$userAccount->isEligibleTo('project'))
            return back()->withErrors([
                'The operation could not complete because you have reached the limit.
                 Please consider upgrading to a new plan.'
            ]);
            

        $request->validate([

            'name' => 'required'
        ]);
        DB::table('projects')->insert([

            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);

        return back(); //issue with message; navigate to the projects page
    }

    public function visitProject (Request $request, $project_id){

        $project = DB::table('projects')->where('id', $project_id)->get();
        $projectName = $project[0]->name;
        View::share('project_id', $project_id);
        session(
            ['projectName'=> $projectName]
        );

        $months = DB::table('stats')->select(['month' ,'year'])->where([
            ['user_id', Auth::user()->id],
            ['project_id', $project_id],
        ])->groupBy(['month', 'year'])->get();
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');

        $readMonth = $request->month;
        $readYear = $request->year;

        $usages = $this->getProjectChartDetails($project_id, 'self');

        return view('user.develop.projects.project-home', 
            compact(['months','readMonth', 
            'readYear', 'currentMonth', 'currentYear',
            'usages']));
    }


    public function deleteProject (Request $request,  $project_id) {

        $request->validate([
            'project_id' => 'required'
        ]);

        if ($request->project_id == $project_id){
            $project = Project::find($project_id);
            $project->delete();
        }

        return redirect(route('user_home'))->withErrors([
            'project_deleted'
        ]);
       
    }

    public function renameProject (Request $request, $project_id) {

        $request->validate([
            'name' => 'required'
        ]);

        DB::table('projects')->where([
            ['user_id', Auth::user()->id],
            ['id', $project_id]
        ])->update([
            'name' => $request->name
        ]);

        session(
            ['projectName'=> $request->name]
        );

        return back();

    }

    public function getProjectChartDetails ($project_id, $context='web') {

        $dataSize = DB::table('data')->select(
                        DB::raw('sum(data.size) as data_size')
                        )->where('project_id', $project_id)->get();

        $dataAll = DB::table('data')->select(
            DB::raw('sum(data.size) as data_all')
        )->where('user_id', Auth::user()->id)->get();

        $dataSize = ($dataSize[0])->data_size;
        $dataAll  = ($dataAll[0])->data_all;
        $plan = Plan::find(Auth::user()->plan_id);


        $assetsSize = DB::table('assets')->select(
            DB::raw('sum(assets.size) as assets_size')
        )->where('project_id', $project_id)->get();

        $assetAll = DB::table('assets')->select(
            DB::raw('sum(assets.size) as assets_all')
        )->where('user_id', Auth::user()->id)->get();

        $assetsSize = ($assetsSize[0])->assets_size;
        $assetsAll  = ($assetAll[0])->assets_all;

        if ($context === 'self')
            return [$dataSize, $assetsSize];
        
        return response()->json([
            'dataSize' => $dataSize,
            'dataUsedByOthers' => $dataAll-$dataSize,
            'assetsSize' => $assetsSize,
            'assetsUsedByOthers' => $assetsAll-$assetsSize,
            'plan' => $plan,
        ]);

        
    }

    
}
