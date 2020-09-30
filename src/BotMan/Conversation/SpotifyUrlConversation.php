<?php

declare(strict_types=1);

namespace App\BotMan\Conversation;

use App\Spotify\Track;
use App\SpotifyTrackConverter;
use BotMan\BotMan\BotMan;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use Throwable;

class SpotifyUrlConversation
{
    private const PLAYLIST_ID = '3ZCp9kxqMENGMUDuvPJx0P';

    private SpotifyTrackConverter $converter;

    private Spotify $spotify;

    /**
     * Create a new SpotifyUrlConversation.
     */
    public function __construct(SpotifyTrackConverter $converter, Spotify $spotify)
    {
        $this->converter = $converter;
        $this->spotify   = $spotify;
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

            $this->spotify->addPlaylistTracks(self::PLAYLIST_ID, $track->getId());
        } catch (Throwable $t) {
            $bot->reply('I wasn\'t able to parse that URL.  Sorry!');
            $bot->reply($t->getMessage());

            return;
        }
    }
}
