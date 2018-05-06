<?php

namespace App\Spotify;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class Track
{
    private const SPOTIFY_URL = 'https://open.spotify.com/track';

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromUrl(string $url)
    {
        self::validateUrl($url);
        $id = self::parseUrl($url);

        return new self($id);
    }

    private static function validateUrl(string $url) : void
    {
        if (strpos($url, self::SPOTIFY_URL) === false) {
            throw new \UnexpectedValueException('Received an invalid Spotify URL.');
        }

        return;
    }

    private function parseUrl(string $url) : string
    {
        return substr(strrchr($url, '/'), 1);
    }

    public function getId() : string
    {
        return $this->id;
    }
}
