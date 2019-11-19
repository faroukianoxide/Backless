<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Auth as Authorise;

use App\Project;
use App\AppUser;
use App\Helpers\StringHelper;

class AuthController extends Controller
{



    public function showAllAuths ($project_id) {

        $project = Project::find($project_id);
        $auths = $project->auths()->where('user_id', Auth::user()->id)->get();

        return view('user.develop.projects.auth.auth-home', compact('auths', 'project_id'));
    }


    public function deleteAuth (Request $request, $project_id, $id) {

        if ($request->auth_id == $id) {
            $project = Project::find ($project_id);
            $project->auths()->where([
                ['user_id', Auth::user()->id],
                ['id', $id]
            ])->delete();
        }
        

        return back(); //issue: with message
    }

    public function verify (Request $request, $project_id, $mode = 'api') {
        
        if ($request->credentials == '' || $request->encrypted == '') {
            return 'no_credentials';
            
        }
        // just to be sure
        $request->validate([
            'credentials' => 'required',
            'encrypted' => 'required'
        ]);
            
        $constraints = $request ->credentials;
        $constraintsArray = explode(',', $constraints);
        $whereClauseArray = [];
        if ($request->encrypted) {
            
            $parameters = explode ('=', $request->encrypted);
            $encryptedField = $parameters[0];
            $encryptedValue = $parameters[1];
        }

        

        foreach ($constraintsArray as $constraint) {
           $parameters = explode ('=', $constraint);
           array_push($whereClauseArray,
            "data REGEXP '\"\s*$parameters[0]\s*\"\s*:\s*\"\s*$parameters[1]\s*\"' ");
            //issue: i love the way i inserted the id
        }
        $user_id = Auth::user()->id;

        $whereClause = implode('and', $whereClauseArray)."and project_id = $project_id and user_id = $user_id";

        //issue remember that the id must be equal to the user id
        $data = DB::table('auths')->select('data')->whereRaw($whereClause)->get();
        

        if (count($data) == 0)
            if ($mode == 'app')
            return response()->json(['error' => "record not found"] , 404);
            else return response('No such credentials', 404);

        //$data = json)

        $data = json_decode($data);
        $data = (array)json_decode($data[0]->data);
        
        if (password_verify($encryptedValue, $data[$encryptedField])) {
            
            if ($mode == 'app')
                
                return response()->json($data, 200);
            else
                return "OK";
                //return true;
        }
        else
            if ($mode == 'app')
                return response()->json(['error' => 'incorrect'], 200);
            return "incorrect_password";


    }

    public function makeAuth (Request $request, $project_id) {

       $request->validate([
           'data' => 'required',
           'unique' => 'required',
           'encrypt' => 'required',
           ]);

        if (!StringHelper::isJSON($request->data))
            return response('data is not in JSON', 412);

       if ($request->encrypt) {
           $encrypt = $request->encrypt;
           $data = (array) json_decode($request->data);
           $data[$encrypt] = bcrypt($data[$encrypt]);
           $data['encrypted'] = $request->encrypt;
           $data = json_encode($data);
       }
       $dataArray = (array) json_decode($request->data);
       $uniqueKey = $request->unique;
       $uniqueValue = $dataArray[$uniqueKey];

       if (!$this->isUnique($uniqueKey, $uniqueValue)) {
            return response(['message' => 'duplicate value for '.$request->unique], 412);
       }

       $project = Project::find($project_id);

       $project->auths()->create([
           'data' => $data,
            'user_id' => Auth::user()->id
       ]);

       return response('success', 200);
    }

    public function isUnique ($key, $value) {

        $query = "data REGEXP '\"\s*$key\s*\"\s*:\s*\"\s*$value\s*\"' ";
        $data = DB::table('auths')->select('data')->whereRaw($query)->get();

        if (count($data)){
            return false;
        }

        return true;

    }



}
