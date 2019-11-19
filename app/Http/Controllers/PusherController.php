<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherController extends Controller
{
    private $pusher;

    public function __construct (Pusher $pusher) {

        $this->pusher = $pusher;

    }

    public function getPusherInstance () {
        return $this->pusher;
    }

    public function authorizeClient () {

        return response($this
        ->pusher->socket_auth($request->channel_name, $request->socket_id));
    }
}
