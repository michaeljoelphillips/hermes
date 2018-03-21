<?php

namespace App\Http\Controllers;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class BotManController
{
    public function __construct()
    {
    }

    public function chatAction()
    {
        resolve(BotMan::class);
    }
}
