<?php

declare(strict_types=1);

namespace App\Twitch;

interface TokenStorage
{
    public function getToken(): ?AccessToken;

    public function setToken(AccessToken $token): void;
}
