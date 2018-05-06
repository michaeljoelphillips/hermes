<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class SpotifyProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SpotifyWebAPI::class, function ($app) {
            $session = new Session(
                config('spotify.client_id'),
                config('spotify.client_secret')
            );

            $session->requestCredentialsToken();

            $spotify = new SpotifyWebAPI();
            $spotify->setAccessToken($session->getAccessToken());

            return $spotify;
        });
    }
}
