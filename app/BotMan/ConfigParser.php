<?php

namespace App\BotMan;

use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class ConfigParser
{
    /** @var array */
    protected $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function configure(BotMan $bot)
    {
        foreach ($this->messages as $hears => $replies) {
            $bot->hears($hears, $this->reply($replies));
        }
    }

    private function reply(string $reply)
    {
        return function (BotMan $bot) {
            $bot->reply($reply);
        };
    }
}
