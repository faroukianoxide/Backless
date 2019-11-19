<?php

namespace App\Helpers;

use DB;

class EventBinder
{


    public static function bindData($data_id, $user_id, $pusher)
    {

        $listener = DB::table('data')->select(['listener', 'content'])
            ->where('id', $data_id)->get();
        $content = ($listener[0])->content;
        if ($listener != null) {
            $listener = ($listener[0])->listener;
            if ($listener == null)
                return;
            $explode = explode('.', $listener);
            $channel = $explode[0];
            $event =  $explode[1];

            if (DB::table('channels')->where([
                ['user_id', $user_id],
                ['channel', $channel],
            ])->exists()) {
                
                $pusher->trigger($channel, $event, 
                ['id' => $data_id,
                'data' => json_decode($content)
                ]);
            }
        }
    }
}
