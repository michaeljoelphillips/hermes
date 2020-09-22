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
        $this->botman->listen();
    }
}
