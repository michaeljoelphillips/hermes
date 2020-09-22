<?php

declare(strict_types=1);

namespace Test\Unit\Spotify;

use App\Spotify\Track;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class TrackTest extends TestCase
{
    public function testFromUrl(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Received an invalid Spotify URL.');

        $subject = Track::fromUrl('https://google.com');
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetId(string $url, string $track): void
    {
        $subject = Track::fromUrl($url);

        $this->assertEquals($track, $subject->getId());
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function urlProvider(): array
    {
        return [
            [
                'https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ',
                '37G9ACbVFCdZvdHVSA3dxz',
            ],
            [
                'https://open.spotify.com/track/5GoojLvO6NMf72XGgRyguv',
                '5GoojLvO6NMf72XGgRyguv',
            ],
        ];
    }
}
