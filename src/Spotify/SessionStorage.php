<?php

declare(strict_types=1);

namespace App\Spotify;

use SpotifyWebAPI\Session;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function serialize;
use function sprintf;
use function unserialize;

class SessionStorage
{
    private const SERIALIZED_FILENAME = 'spotify.session';

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getSession(): ?Session
    {
        if (file_exists($this->path()) === false) {
            return null;
        }

        $session = unserialize(file_get_contents($this->path()));

        $session->refreshAccessToken();
        $this->setSession($session);

        return $session;
    }

    public function setSession(Session $session): void
    {
        $session = serialize($session);

        file_put_contents($this->path(), $session);
    }

    private function path(): string
    {
        return sprintf('%s/%s', $this->path, self::SERIALIZED_FILENAME);
    }
}
