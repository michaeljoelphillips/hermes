<?php

namespace Tests\Integration;

use Tests\TestCase;
use Google_Service_YouTube as Youtube;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use App\SpotifyTrackConverter;
use App\Spotify\Track;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class SpotifyTrackConverterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->youtube = $this->app->make(Youtube::class);
        $this->spotify = $this->app->make(Spotify::class);

        $this->subject = new SpotifyTrackConverter($this->youtube, $this->spotify);
    }

    public function testConvert()
    {
        $track = Track::fromUrl('https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ');

        $video = $this->subject->convert($track);

        $this->assertEquals('https://youtube.com/watch?v=Tu9KgGqXDyw', $video->getUrl());
    }
}
