<?php

declare(strict_types=1);

namespace App\Spotify;

use UnexpectedValueException;

use function strpos;
use function strrchr;
use function strrpos;
use function substr;

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
    public static function fromUrl(string $url): self
    {
        self::validateUrl($url);
        $id = self::parseUrl($url);

        return new self($id);
    }

    /**
     * Validate the URL.
     *
     * @throws UnexpectedValueException If $url is not a Spotify Track URL.
     */
    private static function validateUrl(string $url): void
    {
        if (strpos($url, self::SPOTIFY_URL) === false) {
            throw new UnexpectedValueException('Received an invalid Spotify URL.');
        }

        return;
    }

    /**
     * Retreive the Track ID from the URL.
     */
    private static function parseUrl(string $url): string
    {
        $track       = substr(strrchr($url, '/'), 1);
        $queryString = strrpos($track, '?');

        if ($queryString !== false) {
            $track = substr($track, 0, $queryString);
        }

        return $track;
    }

    /**
     * Get the Track ID.
     */
    public function getId(): string
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
