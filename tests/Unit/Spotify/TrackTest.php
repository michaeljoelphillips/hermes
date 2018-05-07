<?php

namespace Test\Unit\Spotify;

use PHPUnit\Framework\TestCase;
use App\Spotify\Track;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class TrackTest extends TestCase
{
    public function testFromUrl()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Received an invalid Spotify URL.');

        $subject = Track::fromUrl('https://google.com');
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGetId(string $url, string $track)
    {
        $subject = Track::fromUrl($url);

        $this->assertEquals($track, $subject->getId());
    }

    public function urlProvider()
    {
        return [
            [
                'https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ',
                '37G9ACbVFCdZvdHVSA3dxz'
            ],
            [
                'https://open.spotify.com/track/5GoojLvO6NMf72XGgRyguv',
                '5GoojLvO6NMf72XGgRyguv'
            ]
        ];
    }
}
