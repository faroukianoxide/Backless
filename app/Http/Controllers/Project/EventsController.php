<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Channel;
use \Pusher\Pusher;
use DB;

class EventsController extends Controller
{

    private $pusher;

    public function __construct (Pusher $pusher) {

        $this->pusher = $pusher;
    }


    public function triggerEvent ($project_id, $channel, $event, Request $request) {

        if (DB::table('channels')->where([
            ['user_id', Auth::user()->id],
            ['channel', $channel],
            ['project_id', $project_id]
        ])->exists()) {
            $this->pusher->trigger($channel, $event, json_decode($request->payload));
            return response('Event published successfully', 200);
        } else {
            return response('Channel does not exist', 404);
        }
    }



}
