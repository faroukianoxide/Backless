<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Pusher\Pusher;

class PusherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Pusher\Pusher', function ($app) {
            return new Pusher (
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                array(
                    'cluster' => env('PUSHER_APP_CLUSTER')
                )
            );
        });

        
        
    }
}
