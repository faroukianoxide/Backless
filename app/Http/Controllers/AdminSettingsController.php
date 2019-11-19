<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class AdminSettingsController extends Controller

{

    public function __construct () {

        $this->middleware('admin');

    }

    public function showPasswordPage () {

        $message = null;

        return view ('admin.change_password', compact('message'));
    }

    public function changePassword (Request $request) {

        $request->validate([

            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required_with:new_password'
         ]);

        //compare the entered password to the old password
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $message  = 'failure';
            return view('admin.change_password', compact('message'));
        }

        $message = 'success';
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return view ('admin.change_password', compact('message'));


    }

    public function showProfilePage () {

        $user = Auth::user();
        $message = null;
        return view('admin.change_profile', compact(['user', 'message']));

    }

    public function changeProfile (Request $request) {

        $request->validate ([

            'name' => 'required',
            'email' => 'required|email'

        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $message = 'success';
        return view('admin.change_profile', compact(['user', 'message']));

    }


}
