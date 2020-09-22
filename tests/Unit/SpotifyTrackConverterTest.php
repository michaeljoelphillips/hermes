<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Spotify\Track;
use App\SpotifyTrackConverter;
use Google_Service_YouTube as Youtube;
use PHPUnit\Framework\TestCase;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;

class SpotifyTrackConverterTest extends TestCase
{
    public function setUp(): void
    {
        $this->youtube = $this->createMock(Youtube::class);
        $this->spotify = $this->createMock(Spotify::class);

        $this->subject = new SpotifyTrackConverter($this->youtube, $this->spotify);
    }

    public function testConvert(): void
    {
        $track = Track::fromUrl('https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ');

        $this
            ->spotify
            ->method('getTrack')
            ->willReturn($this->getSpotifyTrack());

        $this->markTestIncomplete(
            'I need to mock the `search` property on `$this->youtube`.'
        );

        $this->subject->convert($track);
    }

    private function getSpotifyTrack(): stdClass
    {
        return (object) [
            'name' => 'Head in the Ceiling Fan',
            'artists' => ['name' => 'Title Fight'],
        ];
    }
}
