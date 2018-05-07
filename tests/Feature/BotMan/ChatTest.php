<?php

namespace Tests\Feature\BotMan;

use BotMan\Studio\Testing\BotManTester;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class ChatTest extends BotManTester
{
    public function testChat()
    {
        $this
            ->bot
            ->receives('Hi!')
            ->assertReply('Hello!');
    }
}
