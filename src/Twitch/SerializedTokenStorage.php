<?php

declare(strict_types=1);

namespace App\Twitch;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function serialize;
use function sprintf;
use function unserialize;

class SerializedTokenStorage implements TokenStorage
{
    private const SERIALIZED_FILENAME = 'twitch.token';

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getToken(): ?AccessToken
    {
        if (file_exists($this->path()) === false) {
            return null;
        }

        return unserialize(file_get_contents($this->path()));
    }

    public function setToken(AccessToken $token): void
    {
        $token = serialize($token);

        file_put_contents($this->path(), $token);
    }

    private function path(): string
    {
        return sprintf('%s/%s', $this->path, self::SERIALIZED_FILENAME);
    }
}
