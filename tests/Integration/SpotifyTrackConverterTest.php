<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Spotify\Track;
use App\SpotifyTrackConverter;
use PHPUnit\Framework\TestCase;

class SpotifyTrackConverterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $container = require_once __DIR__ . '/../../bootstrap.php';

        $this->subject = $container->get(SpotifyTrackConverter::class);
    }

    public function testConvert(): void
    {
        $track = Track::fromUrl('https://open.spotify.com/track/37G9ACbVFCdZvdHVSA3dxz?si=iZxqHIsiR7W_2dK6hUWanQ');

        $video = $this->subject->convert($track);

        $this->assertEquals('https://youtube.com/watch?v=Tu9KgGqXDyw', $video->getUrl());
    }
}
