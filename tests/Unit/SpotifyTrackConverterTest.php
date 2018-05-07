<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Google_Service_YouTube as Youtube;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use App\SpotifyTrackConverter;
use App\Spotify\Track;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class SpotifyTrackConverterTest extends TestCase
{
    public function setUp()
    {
        $this->youtube = $this->createMock(Youtube::class);
        $this->spotify = $this->createMock(Spotify::class);

        $this->subject = new SpotifyTrackConverter($this->youtube, $this->spotify);
    }

    public function testConvert()
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

    private function getSpotifyTrack()
    {
        return (object) [
            'name' => 'Head in the Ceiling Fan',
            'artists' => [
                'name' => 'Title Fight'
            ]
        ];
    }

    private function getYoutubeResults()
    {
        return (object) [
            'items' => [
                [
                    'id' => [
                        'videoId' => 'Tu9KgGqXDyw',
                    ],
                ],
            ],
        ];
    }
}
