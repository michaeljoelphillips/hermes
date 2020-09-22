<?php

declare(strict_types=1);

namespace App\Controllers;

use BotMan\BotMan\BotMan;

class BotManController
{
    protected BotMan $botman;

    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function chat(): void
    {
        $this->botman->listen();
    }
}
