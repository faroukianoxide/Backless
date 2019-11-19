<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Auth;
use App\Asset;
use App\Helpers\StringHelper;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    //

    private $userAccount;

    public function __construct()
    {
        $this->userAccount = new UserAccountsController ();
    }

    public function showAll (Request $request, $project_id) {
        
       $project = Project::find($project_id);
       $path = $request->path;
       if ($path == '')
            $path = 'root';

       $folders = DB::table('folders')->select([
           'name', 'path', 'created_at'
       ])->where([
           ['user_id', Auth::user()->id],
           ['project_id', $project_id],
           ['parent', $path],
       ])->get();

       $assets = $project->assets()->where([
           ['user_id', Auth::user()->id],
           ['parent_folder_path', $path]
       ])->get();

     
       

       return view('user.storage.storage', 
       compact(['assets', 'project_id', 'path', 'folders']));
    }

    public function createNewFile($project_id, Request $request) {

        if (!$this->userAccount->isEligibleTo('storage'))
            if ($request->mode == 'web')
                return back()->withErrors([
                    'The operation could not complete because you have
                    reached your storage limit.
                     Please consider upgrading to a new plan.'
                ]);
            else return response('our_of_space', 403);

        $storage_location = 'root';
        if (strlen($request->path)>0)
            $storage_location = $request->path;


        $request->validate(['file' => 'required']);

        $fileName = $request->file('file')->getClientOriginalName();
        //return response ($fileName);
       // $fileSize = 
        $fileContent = $request->file('file');
        
        $uploadMethod = getenv('STORAGE_METHOD');

        $fileToken = StringHelper::createRandomString(10);
        if ($uploadMethod == 's3')
            $location = getenv('S3_FOLDER');
        else if ($uploadMethod == 'dropbox')
            $location = '/';
        else {
            $location = 'local_cloud_uploads';
            $uploadMethod = 'local';
        }

        

        if (
            $path = Storage::disk($uploadMethod)->put(env('APP_NAME').'_users/', $fileContent )
        ) {
            $size = Storage::disk($uploadMethod)->size($path);
            Asset::create([
                'file_native_name' => $fileName,
                'file_cloud_name' => $path, //should be file_cloud_url
                'user_id' => Auth::user()->id,
                'project_id' => $project_id,
                'storage_type' => $uploadMethod,
                //'file_size' => $fileSize,
                'access_url' => $fileToken,
                'parent_folder_path' => $storage_location,
                'size' => $size
            ]);
        } 

        if ($request->mode == 'web')
            return back();
        else
            return response()->json(['file_token' => $fileToken]);

    }

    public function createNewFolder (Request $request, $project_id) {

        $request->validate(['name' => 'required']);

        DB::table('folders')->insert([
            'parent' => $request->parent,
            'name' => $request->name,
            'path' => $request->parent.'/'.$request->name,
            'project_id' => $project_id,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return back();

    }

    function deleteFolder (Request $request, $project_id) {

        $path = $request->folder_path;

        $this->destroyFolder($path, $project_id);

        //delete from cloud

        return response()->json(null, 200);
    }



    function destroyFolder ($path, $project_id) {

        $assets = DB::table('assets')->select(['file_cloud_name', 'storage_type'])
        ->where([
            ['parent_folder_path', $path],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->get();

        foreach ($assets as $asset) {
            Storage::disk($asset->storage_type)
               ->delete($asset->file_cloud_name);
       }

       DB::table('assets')->where([
        ['parent_folder_path', $path],
        ['project_id', $project_id],
        ['user_id', Auth::user()->id],
    ])->delete();

        $folders = DB::table('folders')->select('path')->where([
            ['parent', $path],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->get();

        //recursively destroys the children of the folder
        
        foreach ($folders as $folder) {
            $this->destroyFolder($folder->path, $project_id);
        }

        DB::table('folders')->where([
            ['path', $path],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->delete();        

    }

    public function downloadAsset ($project_id, $id) {

        $result = DB::table('assets')
        ->select(['file_cloud_name', 'storage_type']) //issue:filecloudpath not name
        ->where([
            ['user_id', Auth::user()->id],
            ['project_id', $project_id],
            ['id', $id],
        ])->get();
        
        $fileURL = ($result[0])->file_cloud_name;
        $storageMethod = ($result[0])->storage_type;   
       
        $headers = [
            'Content-Type' => '*',
            'Content-Disposition' => 'attachment; filename= '
            .$fileURL,
        ];

        return Response::make(Storage::disk($storageMethod)->get(
            $fileURL), 200, $headers
        );   

    }

    public function streamAsset ($project_id, $token) {

        $result = DB::table('assets')
        ->select(['file_cloud_name', 'storage_type']) //issue:filecloudpath not name
        ->where([
            
            ['project_id', $project_id],
            ['access_url', $token],
        ])->get();
        
        $fileURL = ($result[0])->file_cloud_name;
        $storageMethod = ($result[0])->storage_type;   
       
        $headers = [
            'Content-Type' => '*',
            'Content-Disposition' => 'attachment; filename= '
            .$fileURL,
        ];

        return Response::make(Storage::disk($storageMethod)->get(
            $fileURL), 200, $headers
        );   

    }

    public function deleteFile (Request $request, $project_id) {

        $asset = DB::table('assets')->select(['file_cloud_name', 'storage_type'])
        ->where([
            ['id', $request->file_id],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->get();

        $asset = $asset[0];
        Storage::disk($asset->storage_type)->delete($asset->file_cloud_name);
        DB::table('assets')->where([
            ['id', $request->file_id],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->delete();

        return back();

    }

    public function deleteFileFromAPI (Request $request, $project_id) {

        $asset = DB::table('assets')->select(['file_cloud_name', 'storage_type'])
        ->where([
            ['access_url', $request->token],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->get();

        $asset = $asset[0];
        Storage::disk($asset->storage_type)->delete($asset->file_cloud_name);
        DB::table('assets')->where([
            ['id', $request->file_id],
            ['project_id', $project_id],
            ['user_id', Auth::user()->id],
        ])->delete();

        return response()->json();

    }

    public function downloadAssetFromAPI (Request $request, $fileToken) {

        $result = DB::table('assets')
        ->select(['file_cloud_name', 'storage_type']) //issue:filecloudpath not name
        ->where([
            ['user_id', Auth::user()->id],
            ['access_url', $fileToken],
        ])->get();
        
        $fileURL = ($result[0])->file_cloud_name;
        $storageMethod = ($result[0])->storage_type;   
       
        $headers = [
            'Content-Type' => '*',
            'Content-Disposition' => 'attachment; filename= '
            .$fileURL,
        ];

        return Response::make(Storage::disk($storageMethod)->get(
            $fileURL), 200, $headers
        );   

    }



    public function deleteProjectAssets ($project_id) {

        //get All folder for this project
        //delete them one after the other


    }

}
