<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Helpers\EventBinder;
use Illuminate\Http\Request;
use \Pusher\Pusher;

class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $userAccount;

    public function __construct()
    {
        $this->middleware('auth');
        $this->userAccount = new UserAccountsController ();
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */



    public function showAll  ($project_id) {

        $user = Auth::user();

        $records = $user->projects()->where('id', $project_id)->first()
            ->data()->get();

        return view('user.data.all-data', compact('records', 'project_id'));

    }


    public function showDataProperties ($project_id, $id) {
        try {
            $data = DB::table('data')->where([
                ['user_id', Auth::user()->id],
                ['project_id', $project_id],
                ['id', $id]
                ])->get();
            $data = $data[0];
        }catch (\Exception $e){
            $errors  = new \Illuminate\Support\MessageBag();
            $errors->add('out_of_bound', 'The data object of id '.$id.' does not exist');
            return back()->withErrors($errors);
        }

        return view('user.data.data_properties', compact('data','project_id'));

    }

    public function createNewData (Request $request, $project_id) {


        if (!$this->userAccount->isEligibleTo('store'))
            return back()->withErrors([
                'The operation did not complete because you have reached your data store limit.
                 Please consider upgrading to a new plan.
                '
                ]);

        $request->validate([

            'content' => 'required',
        ]);


        if ( ! $this->isJSON($request->content)) {

            $errors  = new \Illuminate\Support\MessageBag();
            $errors->add('json_error', 'Your data is not in JSON');

            return back()->withErrors($errors)->withInput();
        }

        DB::table('data')->insert([
            'project_id' => $project_id,
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'listener' => $request->listener,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            
        ]);

        return back();

    }

    public function updateDataDetails ($project_id, $id, Pusher $pusher) {

        if (!$this->userAccount->isEligibleTo('store'))
            return back()->withErrors([
                'The operation did not complete because you are out of space,
                please consider upgrading to a new plan.
                '
            ]);

        request()->validate([

            'content' => 'required',
        ]);


        if ( ! $this->isJSON(request('content'))) {

            $errors  = new \Illuminate\Support\MessageBag();
            $errors->add('json_error', 'Your data is not in JSON');

            return back()->withErrors($errors)->withInput();
        }

        DB::table('data')->where([
            ['id', $id],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id]
            ])->update([

            'content' => request('content'),
            'listener' => request('listener'),
            'updated_at' => Carbon::now()->toDateTimeString(), 
            'size' => strlen(request('content'))
        ]);
        

        EventBinder::bindData($id, Auth::user()->id, $pusher);
        
        return back();

    }

    public function deleteData ($project_id, $id) {

        DB::table('data')->where([
            ['id', $id],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id]
            ])->delete();

        return redirect("projects/$project_id/data");

    }

    

    public function isJSON ($string){

        return is_string($string) && is_array(json_decode($string, true)) ? true : false;

    }


}
