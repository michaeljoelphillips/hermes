<?php

namespace Test\Unit\Spotify;

use PHPUnit\Framework\TestCase;
use App\Spotify\Track;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class TrackTest extends TestCase
{
    public function testFromUrl()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Received an invalid Spotify URL.');

        $subject = Track::fromUrl('https://google.com');
    }

    public function testGetId()
    {
        $subject = Track::fromUrl('https://open.spotify.com/track/5GoojLvO6NMf72XGgRyguv');

        $this->assertEquals('5GoojLvO6NMf72XGgRyguv', $subject->getId());
    }
}
