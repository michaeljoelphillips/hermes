<?php

namespace App\BotMan\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\BotMan;
use App\SpotifyTrackConverter;
use App\Spotify\Track;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class SpotifyUrlConversation
{
    /**
     * Create a new SpotifyUrlConversation.
     *
     * @param SpotifyTrackConverter $converter
     */
    public function __construct(SpotifyTrackConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Convert a Spotify Track URL to a Youtube Video URL.
     *
     * @param BotMan $bot
     * @return void
     */
    public function convertUrl(BotMan $bot) : void
    {
        $message = $bot->getMessage();
        $url = $message->getText();

        try {
            $track = Track::fromUrl($url);
            $video = $this->converter->convert($track);

            $bot->reply($video->getUrl());
        } catch (\UnexpectedValueException $e) {
            $bot->reply('I wasn\'t able to parse that URL.  Sorry!');

            return;
        }
    }
}
