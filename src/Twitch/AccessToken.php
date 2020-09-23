<?php

declare(strict_types=1);

namespace App\Twitch;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use stdClass;

use function sprintf;

class AccessToken
{
    private string $token;

    private string $type;

    private DateTimeInterface $expiry;

    public function __construct(string $token, string $type, DateTimeInterface $expiry)
    {
        $this->token  = $token;
        $this->type   = $type;
        $this->expiry = $expiry;
    }

    public static function fromApiResponse(stdClass $token): self
    {
        $expiry = new DateTimeImmutable();
        $expiry = $expiry->add(new DateInterval(sprintf('PT%sS', $token->expires_in)));

        return new self($token->access_token, $token->token_type, $expiry);
    }

    public function hasExpired(): bool
    {
        return $this->expiry < new DateTimeImmutable();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExpiry(): DateTimeInterface
    {
        return $this->expiry;
    }

    public function __toString(): string
    {
        return sprintf('Bearer %s', $this->getToken());
    }
}
