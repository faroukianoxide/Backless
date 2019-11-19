<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class RequestController extends Controller

{
    

    public function getTraffic (Request $request, $project_id) {
        
        /*$month  = Carbon::now()->format('F');
        $monthInt = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');*/
        
        $month = $request->month;
        $monthInt = date('m', strtotime($month));
        $year = $request->year;
        $constraint = [ 
        ['month', $month],
        ['type', 'request'],
        ['year', $year],
        ['project_id', $project_id]];
        $results = DB::table('stats')->select(['count', 'day'])
            ->where($constraint)->get();
        
        $monthLength = date('t', mktime(0,0,0,$monthInt,1,$year));
        $valuesArray = array_fill(0, $monthLength, 0);

		foreach ($results as $result) {
			$day = $result->day;
            $valuesArray[intval($day - 1)] = intval($result->count);        
        }
        
         
        $daysArray = range(1, $monthLength);

        return response()->json(['values' => $valuesArray,'days'  =>  $daysArray]);

    }

    public function getPlatformTraffic (Request $request) {

        $month = $request->month;
        $monthInt = date('m', strtotime($month));
        $year = $request->year;
        $constraint = [ 
        ['month', $month],
        ['type', 'request'],
        ['year', $year],
        ];
        $results = DB::table('stats')->select(['count', 'day'])
            ->where($constraint)->get();
        
        $monthLength = date('t', mktime(0,0,0,$monthInt,1,$year));
        $valuesArray = array_fill(0, $monthLength, 0);

		foreach ($results as $result) {
			$day = $result->day;
            $valuesArray[intval($day - 1)] = intval($result->count);        
        }
        
         
        $daysArray = range(1, $monthLength);

        return response()->json(['values' => $valuesArray,'days'  =>  $daysArray]);

        
    }
}
