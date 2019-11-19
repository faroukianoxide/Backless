<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use User;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{
    public function __construct () {

        $this->middleware('auth');

    }


    public function changeAuthToken (Request $request) {

        if($request->change_auth != 1)
             return back();
        $tokens = Auth::user()->tokens;

        foreach ($tokens as $token){
            $token->revoke();
        }
        // issue: we need a try-catch here

        $newToken= Auth::user()->createToken('PeerCloud')->accessToken;
        $user = Auth:: user();
        $user->auth_token = $newToken;
        $user->date_issued = Carbon::now()->toDateTimeString();
        $user->date_changed = Carbon::now()->toDateTimeString();
        $user->expiry_date =  $user->tokens[0]->expires_at;
        $user->save();
        return redirect('/user/request_auth');

     }

     public function deleteAccount (Request $request) {

        if ($request->delete_account != 1)
            return 'incomplete request';

        $userId = Auth::user()->id;
        Auth::logout();
        User::find($userId)->delete(); // issue: revoke the token
        //delete data
        DB::table('data')->where('user_id', $userId)->delete();
        //delete media
        $mediaFileNames = DB::table('media')
            ->select('filename')
            ->where('user_id',$userId)
            ->get();

        foreach ($mediaFileNames as $mediaFileName) {
            Storage::delete('media-assets/'.$mediaFileName->filename);
            DB::table('media')->where('user_id',$userId)->delete();
            //issue: we need a try catch here
        }
        $fileNames = DB::table('files')->select('filename')->where('user_id',$userId)->get();
        foreach ($fileNames as $fileName) {
            Storage::delete('uploaded-files/'.$fileName->filename);
            DB::table('files')->where('user_id',$userId)->delete();
            //issue: we need a try catch here
        }
        //issue: delete logs, personal access token
        return redirect('/'); //issue: create a message

    }

    public function changeUserProfile (Request $request) {


        $request->validate([
            'name' =>  'required',
            'email' =>  'required|email'
        ]); // you should adopt a style, Facades or helpers?

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        $this->success = 'Profile changed successfully';

        return redirect('/user/change_profile');

    }

    public function changeUserPassword (Request $request) {

        // prepare the messages
        $user = Auth::user();
        $faults = null;
        $success = null;

        $request->validate([

            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',

        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)){
            $faults = 'The passwords do not match';
            return view('user.change_profile', compact(['success','faults', 'user']));
        }

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();
        $success = 'Password changed successfully';
        return view('user.change_profile', compact(['success','faults','user']));

    }

}
