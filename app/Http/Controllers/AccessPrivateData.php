<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use App\User;
use Auth;
use Validator;
use App\Helpers\LogHelper;
use App\Helpers\StringHelper;
use Carbon\Carbon;

class AccessPrivateData extends Controller

/*
    This controller deals with any request that has to do
    with get data from the endpoints.
    I am using query builder for speed
*/
{

    var $user_id;

    public function __construct () {

        $this->middleware('active'); //verifies account activation
    }



    public function getAll ($project_id) {

        $data = DB::table('data')->select('content', 'id')->where([

            ['user_id', '=', Auth::user()->id],
            ['project_id','=', $project_id]

        ])->get();

        if (count($data) == 0)
            return response()->json(['error '=> 'no data found'], 404);

        $data = json_decode($data);


        $this->user_id = Auth::user()->id;
       // $this->createLog(Carbon::now(), 'Request for all private data', 'data' );
       // just add the the request

        LogHelper::logRequest(Auth::user()->id, $project_id);

        return response()->json($data, 200);

    }

    public function getDataContent ($project_id, $id, $context = 'api') {

        
        $data = DB::table('data')->select(['content'])->where([
            ['project_id','=', $project_id],
            ['id', '=', $id],
            ['user_id', '=', Auth::user()->id]
        ])->get();
       
        if(count($data) == 0)
            return response()->json(['error' => 'Record Not Found'], 404);
        
       //print_r($data[0]);
        $content = ($data[0])->content;
        $content = json_decode($content);

        LogHelper::logRequest(Auth::user()->id, $project_id); 
        
        return response()->json($content, 200); //issue: test the ()->json object first in a real app
        
    }

    public function getDataCount ($project_id) {

        $count = DB::table('data')->where([
            ['project_id', '=', $project_id],
            ['user_id', '=', Auth::user()->id],
        ])->count();

        LogHelper::logRequest(Auth::user()->id, $project_id);
        return $count;

    }

    public function getLatestData ($project_id) {

        $latest = DB::table('data')->select(['content', 'id'])
            ->where([
                ['user_id', '=', Auth::user()->id],
                ['project_id', '=', $project_id],
            ])
            ->orderBy('id', 'desc')->paginate(1);
        $latest = $latest->toArray();

        LogHelper::logRequest(Auth::user()->id, $project_id);
        return response($latest["data"]);

    }

    public function getFixedLength ($project_id, $length, $order = 'asc') {

        $latest = DB::table('data')->select(['content', 'id'])
            ->where([
                ['user_id', '=', Auth::user()->id],
                ['project_id', '=', $project_id],
            ])
            ->orderBy('id', $order)->paginate($length);
        $latest = $latest->toArray();

        LogHelper::logRequest(Auth::user()->id, $project_id);
        return response($latest["data"]);



    }

    //added in: v 1.1

    public function paginate ($project_id, $length, $order = 'asc') {

        $latest = DB::table('data')->select(['content', 'id'])
        ->where([
            ['user_id', '=', Auth::user()->id],
            ['project_id', '=', $project_id],
        ])
        ->orderBy('id', $order)->paginate($length);
        $latest = $latest->toArray();
        $formattedResult = [];
        
        $formattedResult['current_page'] = $latest['current_page'];
        if (isset($latest['prev_page_url']))
            $formattedResult['prev_page'] = $latest['current_page'] - 1;
        if (isset($latest["next_page_url"]))
            $formattedResult['next_page'] = $latest['current_page'] + 1;
        
        $formattedResult['first_page'] = 1;
        $formattedResult['total'] = $latest['total'];
        $formattedResult['data'] = $latest['data'];
    
        return response($formattedResult);
    
    }


    public function search (Request $request, $project_id, $action='get', $length=-1, $order='desc') {
       // return $length; 
       
        $request->validate([
            'constraints' => 'required',
            ]);

        $constraints = $request->constraints;
        $getParams = explode(',', $request->get);

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
            //issue: i love the way i inserted the id
        }

        $whereClause = implode($keyWord, $whereClauseArray);

        //issue remember that the id must be equal to the user id
        if ($action == 'get') {
        
        $data = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)->get();
            return $this->resolveGetParameter($data, $getParams, $project_id);
        }


        if ($action == 'count') {
            $count = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)->count();
            return response($count, 200);

        } else if ($action == 'fixed') {
            $request->validate(['get' => 'required']);
            $data = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)
            ->orderBy('id', $order)->paginate($length);
            $data = $data->toArray();

            
            return $this->resolveGetParameter($data['data'], $getParams, $project_id, true);
        
        //return response($data['data'], 200);
        }

        else if ($action == 'paginate') {
            $latest = DB::table('data')->select(['content', 'id'])->
            whereRaw($whereClause.
            'and project_id = '.$project_id.' and user_id = '.Auth::user()->id)
            ->orderBy('id', $order)->paginate($length);

            $latest = $latest->toArray();
        $formattedResult = [];
        
        $formattedResult['current_page'] = $latest['current_page'];
        if (isset($latest['prev_page_url']))
            $formattedResult['prev_page'] = $latest['current_page'] - 1;
        if (isset($latest["next_page_url"]))
            $formattedResult['next_page'] = $latest['current_page'] + 1;
        
        $formattedResult['first_page'] = 1;
        $formattedResult['total'] = $latest['total'];

        $trueData = $this->resolveGetParameter($latest['data'], $getParams, $project_id, true, true);
        $formattedResult['data'] = $trueData;

        return response($formattedResult);


        }


    }

    public function resolveGetParameter ($data, $getParams, $project_id, $isArray = false, $shouldReturnValue = false) {

        if (count($data) == 0)
            return response()->json(['error' => 'No data with such fields'] , 404);

        if (!$isArray)
            $data = json_decode($data);
        if ($getParams[0] == '*')
            return $data;
        $resultArray = [];

        foreach ($data as $datum) {
            $contents = json_decode($datum->content);
            $contents = (array) $contents;
            $tempArray = [];
            foreach ($getParams as $get) {
                try {
                $tempArray[$get] = $contents[$get];
                $tempArray['id'] = $datum->id;
                
                }catch (\Exception $e) {
                    continue;
                }
            }
            //$tempArray['id'] = $datum['id'];
            array_push($resultArray, $tempArray);
        }

        $this->user_id = Auth::user()->id;
        LogHelper::logRequest(Auth::user()->id, $project_id);
        if (count($resultArray))
            if (!$shouldReturnValue)
                return response()->json($resultArray, 200);
            else
                return $resultArray;
        
        return response()->json(['error' => 'No data with such fields'] , 404);

    }

}
