<?php

declare(strict_types=1);

namespace Tests\Unit\Twitch;

use App\Twitch\AccessToken;
use App\Twitch\SerializedTokenStorage;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

use function file_exists;

class SerializedTokenStorageTest extends TestCase
{
    public function testStorageCanSerializeAndDeserialzeToken(): void
    {
        $subject = new SerializedTokenStorage('/tmp');

        $subject->setToken(new AccessToken('abc', 'def', 'bearer', new DateTimeImmutable()));
        $this->assertTrue(file_exists('/tmp/twitch.token'));

        $token = $subject->getToken();

        $this->assertEquals('abc', $token->getToken());
    }
}
