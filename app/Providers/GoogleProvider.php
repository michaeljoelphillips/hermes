<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class GoogleProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Google_Client', function ($app) {
            $client = new Google_Client(config('google.client'));
            $client->setDeveloperKey(config('google.access_token'));

            return $client;
        });
    }
}
