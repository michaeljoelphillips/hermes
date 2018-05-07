<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Service_YouTube;
use Google_Client;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class YoutubeProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Google_Service_Youtube', function ($app) {
            return new Google_Service_YouTube($app->make('Google_Client'));
        });
    }
}
