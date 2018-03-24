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

    /**
     * @param array
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * Configure the bot.
     *
     * @param BotMan $bot
     */
    public function configure(BotMan $bot)
    {
        foreach ($this->messages as $hears => $replies) {
            $bot->hears($hears, $this->reply($replies));
        }
    }

    /**
     * Adds the $reply to the bot.
     *
     * @param string $reply
     */
    private function reply(string $reply)
    {
        return function (BotMan $bot) use ($reply) {
            $bot->reply($reply);
        };
    }
}
