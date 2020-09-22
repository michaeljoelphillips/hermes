<?php

declare(strict_types=1);

namespace Test\Unit\BotMan;

use App\BotMan\ConfigParser;
use App\BotMan\Conversation\OrbsUpdate;
use BotMan\BotMan\BotMan;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

use function is_callable;

class ConfigParserTest extends TestCase
{
    public function testParseMessage(): void
    {
        $config = [
            'messages' => ['Hi!' => 'Hello!'],
        ];

        $subject = new ConfigParser($config['messages'], []);
        $botman  = $this->createMock(BotMan::class);

        $botman
            ->expects($this->once())
            ->method('hears')
            ->with(
                'Hi!',
                $this->callback(static function ($subject) {
                    return is_callable($subject);
                })
            );

        $subject->configure($botman);
    }

    public function testParseConversationWithInvalidConversationString(): void
    {
        $config = [
            'conversations' => ['Invalid Conversation' => 'Invalid Conversation'],
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman  = $this->createMock(BotMan::class);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the given Conversation string: Invalid Conversation');

        $subject->configure($botman);
    }

    public function testParseConversationWithInvalidConversationClass(): void
    {
        $config = [
            'conversations' => ['Fake Conversation' => 'FakeConversation@fakeMethod'],
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman  = $this->createMock(BotMan::class);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse the given Conversation string: FakeConversation@fakeMethod');

        $subject->configure($botman);
    }

    public function testParseConversationWithValidConversationString(): void
    {
        $config = [
            'conversations' => [
                'Orbs' => OrbsUpdate::class . '@run',
            ],
        ];

        $subject = new ConfigParser([], $config['conversations']);
        $botman  = $this->createMock(BotMan::class);

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
