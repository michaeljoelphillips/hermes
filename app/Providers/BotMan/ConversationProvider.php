<?php

namespace App\Providers\BotMan;

use Illuminate\Support\ServiceProvider;
use App\SpotifyTrackConverter;
use Google_Service_Youtube;
use SpotifyWebAPI\SpotifyWebAPI;
use App\BotMan\Conversation\SpotifyUrlConversation;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class ConversationProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SpotifyUrlConversation::class, function ($app) {
            return new SpotifyUrlConversation(
                $app->make(SpotifyTrackConverter::class)
            );
        });
    }
}
