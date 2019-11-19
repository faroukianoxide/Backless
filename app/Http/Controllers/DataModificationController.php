<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Controllers\UserAccountsController;
use Carbon\Carbon;
use App\Helpers\LogHelper;
use App\Helpers\StringHelper;
use App\Helpers\EventBinder;
use Pusher\Pusher;


class DataModificationController extends Controller
{
    private $pusher;
    private $userAccount;

    public function __construct(Pusher $pusher) {
        $this->pusher = $pusher;
        $this->userAccount = new UserAccountsController ();
        
    }

    public function createNewData (Request $request, $project_id) {
        $request->validate([
            'content' => 'required',
            ]);

        if (!StringHelper::isJson($request->content))
            return response()->json(['error'=> 'content not JSON'], 412);
            // issue: bad request

        DB::table('data')->insert([
            'content' => $request->content,
            'user_id' => Auth::user()->id,
            'project_id' => $project_id,
            'listener' => $request->listener,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'size' => strlen($request->content),
        ]);

        $dataID = DB::getPdo()->lastInsertId();

        return response()->json(['success' => 'successful insertion',
         'id' => $dataID], 200);

    }


    public function  deleteData (Request $request, $id){

        $request->validate([
            'delete' => 'required',
            'data_id' => 'numeric'
        ]);

        if ($request->delete != 'true' || $request->data_id != $id)
            return response()->json('No deletion occurred', 400);

        DB::table('data')->where([
            ['user_id', Auth::user()->id],
            ['project_id', $request->project_id],
            ['id', $request->data_id]

        ])->delete();

        //issue: we need exception handlings we need an already deleted notice

        return response('deleted', 200);

    }

    public function replaceObject (Request $request, $project_id, $id) {

        if (!$this->userAccount->isEligibleTo('store'))
            return response ('limit_exhausted', 403);

        if ($request->content != null) {

            if (StringHelper::isJson($request->content)) {
                
                DB::table('data')->where([
                    'user_id'=> Auth::user()->id,
                    'id'=>  $id,
                    'project_id'=> $project_id,
                ])->update([
                    'content' => $request->content,
                    'size' => strlen($request->content),
                ]);            

               EventBinder::bindData($id, Auth::user()->id, $this->pusher);
                
            }else {
                return response(null, 400);
            }
        }

        return response('success', 200);

    }

    public function update (Request $request, $project_id, $id) {

        if (!$this->userAccount->isEligibleTo('store'))
            return response ('limit_exhausted', 403);

        $request->validate([
            'set' => 'required'
        ]);
        $contents = DB::table('data')->select(['content'])->where([
            'project_id' => $project_id,
            'user_id' => Auth::user()->id,
            'id' => $id,
        ])->get();
        $content = $contents[0];
        $content = json_decode ($content->content);
        $content = (array) $content;
        $setArray = explode(',', $request->set);
        try {

            foreach ($setArray as $set) {
                $set = explode('=', $set);
                $prop = $set[0];
                $val = $set[1];
                $content[$prop] = $val;        
            }
            
            DB::table('data')->where([
                'project_id' => $project_id,
                'user_id' => Auth::user()->id,
                'id' => $id,
            ])->update([
                'content' => json_encode($content),
                'size' => strlen(json_encode($content))
                ]);
            EventBinder::bindData($id, Auth::user()->id, $this->pusher);
            
        }catch (\Exception $e) {
            return response()->json('Not found', 400);
        }
        

        return response('success', 200);

    }

    public function changeListener (Request $request, $project_id, $id) {

        DB::table('data')->where([
            'project_id' => $project_id,
            'user_id' => Auth::user()->id,
            'id' => $id,
        ])->update([
            'listener' => $request->listener
            ]);

        return response(['success' => 'listener changed'], 200);

    }

    public function getByConstraint (Request $request, $project_id, $action) {


       
        $request->validate([
            'constraints' => 'required',
            ]);

        $constraints = $request->constraints;

        $constraintsArray = [];
        if (strpos($constraints, '|') ) {
            $keyWord = 'or ';
            $constraintsArray = explode('|', $constraints);
        }
        elseif (strpos($constraints, ',') ) {
            $keyWord = 'and ';
            $constraintsArray = explode(',', $constraints);
        }else {
            $keyWord = '';  
            $constraintsArray = explode(',', $constraints);
        }
        
        $whereClauseArray = [];

        foreach ($constraintsArray as $constraint) {
           $parameters = explode ('=', $constraint);
           array_push($whereClauseArray,
            "content REGEXP '\"\s*$parameters[0]\s*\"\s*:\s*\"\s*$parameters[1]\s*\"' ");
            
        }

        $whereClause = implode($keyWord, $whereClauseArray);
        LogHelper::logRequest(Auth::user()->id, $project_id);

        //issue remember that the id must be equal to the user id
        if ($action == 'delete') {

            $data = DB::table('data')->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)->delete();
            return response('success', 200);


        } else if ($action == 'replace') {
            if (!$this->userAccount->isEligibleTo('store'))
                return response ('limit_exhausted', 403);

            $request->validate(['content'=>'required']);
            if (!StringHelper::isJSON($request->content))
                return response()->json(['error'=>'content not in JSON'], 412);
            $datas = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)->get();

            foreach ($datas as $data) {
                
                $this->doReplace($data->content, $data->id, $project_id);
                EventBinder::bindData($data->id, Auth::user()->id, $this->pusher);
            }



        } else if ($action == 'update') {
            
            $request->validate(['set' => 'required']);
            $datas = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)->get();
            

            foreach ($datas as $data) {
                
                $this->doUpdate($data->id, $data->content, $request->set, $project_id);
                EventBinder::bindData($data->id, Auth::user()->id, $this->pusher);
            }

            return response('success', 200);
        }
        
    }

    public function doUpdate ($id, $content, $setParams, $project_id) {

        //$content = $contents[0];
        $content = json_decode ($content);
        $content = (array) $content;
        $setArray = explode(',', $setParams);
       
        //try {

            foreach ($setArray as $set) {
                $set = explode('=', $set);
                $prop = $set[0];
                $val = $set[1];
                $content[$prop] = $val;        
            }

            
            
            DB::table('data')->where([
                ['project_id', $project_id],
                ['user_id', Auth::user()->id],
                ['id', $id]
            ])->update([
                'content' => json_encode($content),
                'size' => strlen(json_encode($content))
                ]);
                
        EventBinder::bindData($id, Auth::user()->id, $this->pusher);   
        //}catch (\Exception $e) {
          //  return response('error', 500);
        //}

        return response('success', 200);
    }

    public function doReplace ($content, $id, $project_id) {

                
        DB::table('data')->where([
            ['user_id', '=', Auth::user()->id],
            ['id', '=', $id],
            ['project_id', $project_id],
        ])->update([
            'content' => $content,
            'size' => strlen(json_encode($content)),
        ]);            
        
        EventBinder::bindData($id, Auth::user()->id, $this->pusher);

        return response('success', 200);
                
            
    }
}
