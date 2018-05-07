<?php

namespace App\Spotify;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class Track
{
    private const SPOTIFY_URL = 'https://open.spotify.com/track';

    /**
     * @param string $id The Spotify Track ID.
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Create a Track from the URL.
     *
     * @param string $url The Spotify URL.
     */
    public static function fromUrl(string $url) : self
    {
        self::validateUrl($url);
        $id = self::parseUrl($url);

        return new self($id);
    }

    /**
     * Validate the URL.
     *
     * @param string $url
     * @throws UnexpectedValueException If $url is not a Spotify Track URL.
     * @return void
     */
    private static function validateUrl(string $url) : void
    {
        if (strpos($url, self::SPOTIFY_URL) === false) {
            throw new \UnexpectedValueException('Received an invalid Spotify URL.');
        }

        return;
    }

    /**
     * Retreive the Track ID from the URL.
     *
     * @param string $url
     * @return string
     */
    private static function parseUrl(string $url) : string
    {
        $track = substr(strrchr($url, '/'), 1);

        if ($queryString = strrpos($track, '?')) {
            $track = substr($track, 0, $queryString);
        }

        return $track;
    }

    /**
     * Get the Track ID.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getId();
    }
}
