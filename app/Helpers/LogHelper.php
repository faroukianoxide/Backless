<?php
namespace App\Helpers;

use DB;
use Carbon\Carbon;

class LogHelper {


    public static function logRequest ($user_id, $project_id) {

        $month  = Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');
        $day = Carbon::now()->format('d');
        $constraint = [ ['month', $month],
        ['day', $day],
        ['year', $year],
        ['type', 'request'],
        ['user_id', $user_id],
        ['project_id', $project_id]];
        $count = DB::table('stats')->where($constraint)->count(); //issue: look for a better way to do this

        if ($count) {
            DB::table('stats')->where($constraint)->update([
                'count' => DB::raw('count+1')
            ]);
        }else{
            DB::table('stats')->insert([
                'month' => $month,
                'day' => $day,
                'year' => $year,
                'type' => 'request',
                'user_id' => $user_id,
                'count' => 1,
                'project_id' => $project_id

            ]);
        }
    }
}