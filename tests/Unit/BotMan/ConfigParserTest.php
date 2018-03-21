<?php

namespace Test\Unit\BotMan;

use Tests\TestCase;
use App\BotMan\ConfigParser;
use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class ConfigParserTest extends TestCase
{
    public function testParse()
    {
        $config = [
            'messages' => [
                'Hi!' => 'Hello!'
            ]
        ];

        $subject = new ConfigParser($config['messages']);

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
}
