<?php

namespace Test\Unit\BotMan;

use App\BotMan\ConfigParser;
use BotMan\BotMan\BotMan;
use App\BotMan\Conversation\OrbsUpdate;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class ConfigParserTest extends TestCase
{
    public function testParseMessage()
    {
        $config = [
            'messages' => [
                'Hi!' => 'Hello!'
            ]
        ];

        $subject = new ConfigParser($config['messages'], []);
        $botman = $this->createMock(BotMan::class);

        $botman
            ->expects($this->once())
            ->method('hears')
            ->with(
                'Hi!',
                $this->callback(function ($subject) {
                    return is_callable($subject);
                })
            );

        $subject->configure($botman);
    }

    public function testParseConversationWithInvalidConversationString()
    {
        $config = [
            'conversations' => [
                'Invalid Conversation' => 'Invalid Conversation',
            ]
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman = $this->createMock(BotMan::class);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the given Conversation string: Invalid Conversation');

        $subject->configure($botman);
    }

    public function testParseConversationWithInvalidConversationClass()
    {
        $config = [
            'conversations' => [
                'Fake Conversation' => 'FakeConversation@fakeMethod',
            ]
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman = $this->createMock(BotMan::class);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the given Conversation string: FakeConversation@fakeMethod');

        $subject->configure($botman);
    }

    public function testParseConversationWithValidConversationString()
    {
        $config = [
            'conversations' => [
                'Orbs' => OrbsUpdate::class . '@run',
            ]
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman = $this->createMock(BotMan::class);

        $botman
            ->expects($this->once())
            ->method('hears')
            ->with(
                'Orbs',
                'App\BotMan\Conversation\OrbsUpdate@run'
            );

        $subject->configure($botman);
    }
}
