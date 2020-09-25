<?php

declare(strict_types=1);

namespace App\BotMan\Conversation;

use App\Spotify\Track;
use App\SpotifyTrackConverter;
use BotMan\BotMan\BotMan;
use Throwable;

class SpotifyUrlConversation
{
    /**
     * Create a new SpotifyUrlConversation.
     */
    public function __construct(SpotifyTrackConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Convert a Spotify Track URL to a Youtube Video URL.
     */
    public function convertUrl(BotMan $bot): void
    {
        $message = $bot->getMessage();
        $url     = $message->getText();

        try {
            $track = Track::fromUrl($url);
            $video = $this->converter->convert($track);

            $bot->reply($video->getUrl());
        } catch (Throwable $t) {
            $bot->reply('I wasn\'t able to parse that URL.  Sorry!');

            return;
        }
    }
}
