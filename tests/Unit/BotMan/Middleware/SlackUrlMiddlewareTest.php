<?php

declare(strict_types=1);

namespace Test\Unit\BotMan\Middleware;

use App\BotMan\Middleware\SlackUrlMiddleware;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use PHPUnit\Framework\TestCase;

class SlackUrlMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        $this->botman  = $this->createMock(BotMan::class);
        $this->subject = new SlackUrlMiddleware();
    }

    public function testMessageWithOnlyUrl(): void
    {
        $message = new IncomingMessage(
            '<https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7>',
            'Bob',
            'BotMan'
        );

        $next = static function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7', $result);
    }

    public function testMessageWihoutUrl(): void
    {
        $message = new IncomingMessage(
            'Message without URL',
            'Bob',
            'BotMan'
        );

        $next = static function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('Message without URL', $result);
    }

    public function testMessageWithUrl(): void
    {
        $message = new IncomingMessage(
            'Message with URL.  <https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7>',
            'Bob',
            'BotMan'
        );

        $next = static function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('Message with URL.  https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7', $result);
    }
}
