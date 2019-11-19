<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Carbon\Carbon;
use App\Helpers\StringHelper;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function __construct () {

       // $this->middleware('auth', ['except' => array('logout')]);

    }

    public function showLogin () {

        $message = null;

        return view('auth.login', compact('message'));

    }

    public function createNewUser (Request $request, $action) {

        $request->validate([

            'name' =>  'required',
            'email' => 'required|email'
        ]);
        
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $randomString = '';
        if ($action == 'user')
            $input['password'] = bcrypt($request->password);
        else {
            $randomString = StringHelper::createRandomString(6);
            $input['password'] = bcrypt($randomString);
        }

        $user = User::create($input);


        if ($token = $user->createToken('Backless')->accessToken) {

            $user = User::find($user->id);
            $user->auth_token = $token;
            $user->date_issued = Carbon::now()->toDateTimeString();
            $user->status = 'Active';
            $user->last_activated = Carbon::now()->toDateTimeString();
            $user->expiry_date = $user->tokens[0]->expires_at;
            $user->save();

        }

        if ($action == 'admin') {
            //sendEmail($request->email);
            echo $randomString;
            
        }else{
            Auth::login($user);
            return redirect()->route('user_home');
        }

    }

    public function login () {

        $this->validate(request(), [

            'email' => 'required|email',
            'password' => 'required'

        ]);

        if(!Auth::attempt(request(['email','password']))){

            $message = 'wrong details';
            return view('auth.login', compact('message'));

        }

        if(Auth::user()->status == 'admin')
            return redirect()->route('Dashboard');

        return redirect()->route('user_home');


    }

    public function logout () {


        Auth::logout();

        return redirect()->route('login');

    }


}
