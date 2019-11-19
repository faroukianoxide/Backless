<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationsController extends Controller
{
    //

    public function showAll ($device_id) {

        $notifications = Notification::where([
            'user_id' => Auth::user()->id,
            'device_id' => $device_id
            ])
            ->get();

        return view('user.develop.devices.notifications_home', compact(['notifications' , 'device_id']));

    }

    public function create (Request $request, $device_id) {

        $notifications = Notification::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'device_id' => $device_id,
            'data_point' => $request->data_point,
            'channel' => sha1(time())
            ]);

            return back();

    }
}
