<?php

namespace App\Http\Middleware;


use App\Plan;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = Auth::user()->id;
        $plan = Plan::find(Auth::user()->plan_id);
        $usage = DB::table('usage')->select([
            'projects', 'storage_limit', 'store_limit', 'events'
        ])->where('user_id', $user_id)->get();

       // if (user)
        
        
        return $next($request);
    }
}
