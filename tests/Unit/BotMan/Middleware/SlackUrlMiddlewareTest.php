<?php

namespace Test\Unit\BotMan\Middleware;

use PHPUnit\Framework\TestCase;
use BotMan\BotMan\BotMan;
use App\BotMan\Middleware\SlackUrlMiddleware;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class SlackUrlMiddlewareTest extends TestCase
{
    public function setUp()
    {
        $this->botman = $this->createMock(BotMan::class);
        $this->subject = new SlackUrlMiddleware();
    }

    public function testMessageWithOnlyUrl()
    {
        $message = new IncomingMessage(
            '<https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7>',
            'Bob',
            'BotMan'
        );

        $next = function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7', $result);
    }

    public function testMessageWihoutUrl()
    {
        $message = new IncomingMessage(
            'Message without URL',
            'Bob',
            'BotMan'
        );

        $next = function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('Message without URL', $result);
    }

    public function testMessageWithUrl()
    {
        $message = new IncomingMessage(
            'Message with URL.  <https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7>',
            'Bob',
            'BotMan'
        );

        $next = function (IncomingMessage $message) {
            return $message->getText();
        };

        $result = $this->subject->received($message, $next, $this->botman);

        $this->assertEquals('Message with URL.  https://open.spotify.com/track/6ngkl5emCWthVzrkSOVTN7', $result);
    }
}
