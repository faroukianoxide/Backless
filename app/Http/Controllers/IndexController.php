<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\AuthController;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Exception;

class IndexController extends Controller {


    public function createNewIndex(Request $request, $project_id) {
        $request->validate([
            'name' => 'required',
            'map_type' => 'required',
            'is_auth' => 'nullable|boolean',
            'id' => 'required|numeric',
        ]);

        if ($request->is_auth == null)
            $auth = 0;  
        else $auth = $request->is_auth;

        DB::table('indexes')->insert([
            'name' => $request->name,
            'map_type' => $request->map_type,
            'is_authed' => $auth,
            'maps_to' => $request->id,
            'user_id' => Auth::user()->id,
            'project_id' => $project_id,
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ]);

        return back();
    }

    public function showIndexes($project_id) {

        $indexes = DB::table('indexes')
            ->select(['name', 'id', 'map_type', 'maps_to', 'is_authed'])
            ->where([
                ['user_id', Auth::user()->id],
                ['project_id', $project_id]
            ])->get();

        return view('user.data.indexes', compact(['indexes', 'project_id']));
    }


    public function accessByIndex(Request $request, $project_id, $index)
    {

        $result = DB::table('indexes')
            ->select(['map_type', 'is_authed', 'maps_to'])
            ->where([
                ['user_id', Auth::user()->id],
                ['project_id', $project_id],
                ['name', $index],
            ])->get();
        
            $authController = new AuthController();
            $dataController = new AccessPrivateData();
            

        if (count($result)) {
            $result = $result[0];
            if ($result->is_authed == 1) {
                
                $authResult = $authController->verify($request, $project_id, 'server');
               //$authResult = "OK";
                if ($authResult === "OK") {
                    if($result->map_type == 'data')
                        return $dataController->getDataContent($project_id, $result->maps_to, 'server');
                    else
                        $this->serveIndexedStorage($result->maps_to, $project_id);
                }else if ($authResult == "incorrect_password")
                    return response(['error'=>'incorrect password'], 412);
                else if ($authResult === 'no_credentials')
                        return response(['error'=> 'Unauthorized'], 401);
                else
                    return response (['error'=>'no record found'], 404);
            } else  {
                if($result->map_type == 'data')
                        return $dataController->getDataContent($project_id, $result->maps_to, 'server');
                    else
                        return $this->serveIndexedStorage($result->maps_to, $project_id);
            }

            
        }
    }

    public function serveIndexedStorage($id, $project_id) {
        $storageController  = new StorageController ();
        return $storageController->downloadAsset($project_id, $id);
    }

    public function editIndex(Request $request, $project_id, $id) {

        $index = DB::table('indexes')->select([
            'map_type', 'name',
            'is_authed', 'maps_to'
        ])
            ->where([
                ['user_id', Auth::user()->id],
                ['project_id', $project_id],
                ['id', $id],
            ])->get();
        try {
            $index = $index[0];
        }catch (Exception $e) {
            return redirect(route('indexes_home', ['project_id' => $project_id]));
            
        }
            

        return view('user.data.edit_index', compact(['index', 'id', 'project_id']));
    }

    public function saveChanges(Request $request, $project_id, $id) {

        $request->validate([
            'name' => 'required',
            'map_type' => 'required',
            'is_auth' => 'nullable|boolean',
            'id' => 'required|numeric',
        ]);

        if ($request->is_auth == null)
            $auth = 0;
        else $auth = $request->is_auth;

        DB::table('indexes')->where('id', $id)
            ->update([
                'name' => $request->name,
                'map_type' => $request->map_type,
                'is_authed' => $auth,
                'maps_to' => $request->id,
                'updated_at' => Carbon::now()->toDateString(),
            ]);

        return back();
    }

    public function deleteIndex (Request $request, $project_id) {

        DB::table('indexes')->where([
            'user_id' => Auth::user()->id,
            'project_id' => $project_id,
            'id' => $request->index_id
        ])->delete();

        return back();
    }

}
