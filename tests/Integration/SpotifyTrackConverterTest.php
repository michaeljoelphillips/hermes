<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Spotify\Track;
use App\SpotifyTrackConverter;
use Google_Service_YouTube as Youtube;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use Tests\TestCase;

class SpotifyTrackConverterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->youtube = $this->app->make(Youtube::class);
        $this->spotify = $this->app->make(Spotify::class);

        $this->subject = new SpotifyTrackConverter($this->youtube, $this->spotify);
    }

    public function testConvert(): void
    {
        $track = Track::fromUrl('https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ');

        $video = $this->subject->convert($track);

        $this->assertEquals('https://youtube.com/watch?v=Tu9KgGqXDyw', $video->getUrl());
    }
}
