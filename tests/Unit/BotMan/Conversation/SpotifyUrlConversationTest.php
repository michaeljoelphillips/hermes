<?php

declare(strict_types=1);

namespace Tests\Unit\BotMan\Conversation;

use App\BotMan\Conversation\SpotifyUrlConversation;
use App\SpotifyTrackConverter;
use App\Youtube\Video;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use PHPUnit\Framework\TestCase;

class SpotifyUrlConversationTest extends TestCase
{
    public function testConversationConvertsSpotifyTrackToYoutubeVideo(): void
    {
        $botMan    = $this->createMock(BotMan::class);
        $converter = $this->createMock(SpotifyTrackConverter::class);
        $subject   = new SpotifyUrlConversation($converter);

        $botMan
            ->method('getMessage')
            ->willReturn(new IncomingMessage('https://open.spotify.com/track/abc', null, null, null));

        $botMan
            ->expects($this->once())
            ->method('reply')
            ->with('https://youtube.com/watch?v=abc');

        $converter
            ->method('convert')
            ->willReturn(Video::fromId('abc'));

        $subject->convertUrl($botMan);
    }

    public function testConversationGracefullyFailsIfUrlCannotBeParsed(): void
    {
        $botMan    = $this->createMock(BotMan::class);
        $converter = $this->createMock(SpotifyTrackConverter::class);
        $subject   = new SpotifyUrlConversation($converter);

        $botMan
            ->method('getMessage')
            ->willReturn(new IncomingMessage('https://invalid.spotify.com/track/abc', null, null, null));

        $botMan
            ->expects($this->once())
            ->method('reply')
            ->with("I wasn't able to parse that URL.  Sorry!");

        $subject->convertUrl($botMan);
    }
}
