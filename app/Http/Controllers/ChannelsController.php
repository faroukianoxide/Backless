<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Project;

//issue: The channel token is enough as a unique key for this model
//issue: The project name is also enough as a unique key for the project model
// the device name is enough a unique key for devices as well

class ChannelsController extends Controller
{

    public function showChannels ($project_id) {

        $channels = DB::table('channels')->where([
            ['user_id', '=', Auth::user()->id],
            ['project_id', '=', $project_id]
        ])->get();

        return view('user.develop.projects.channels_home', compact(['channels','project_id']));

    }

    public function create ($project_id, Request $request) {

        $project = Project::find($project_id);

        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $project->channels()->create([

            'user_id' => Auth::user()->id,
            'channel' => $request->name,
            'description' => $request->description
        ]);

        return back();

    }

    public function changeChannel (Request $request, $project_id) {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'channel_id' => 'required'
        ]);

        DB::table('channels')->where([
            ['id', $request->channel_id],
            ['user_id', Auth::user()->id],
            ['project_id', $project_id]
        ])->update([
            'channel' => $request->name,
            'description' => $request->description,
        ]);

        return back();

    }

}
