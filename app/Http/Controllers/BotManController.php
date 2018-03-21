<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class BotManController
{
    /** @var BotMan */
    protected $botman;

    /**
     * @param BotMan $botman
     */
    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function chat()
    {
        $this->botman->hears('Hi!', function (BotMan $bot) {
            $bot->reply('Hello!');
        });

        $botman->hears('Can you hear me\?', function (BotMan $bot) {
            $bot->reply('Loud and clear!');
        });
    }
}
