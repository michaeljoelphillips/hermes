<?php

namespace App\BotMan\Middleware;

use Closure;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class SlackUrlMiddleware implements Received
{
    /**
     * Strip the `<` and `>` characters from a URL within a message.
     *
     * @param IncomingMessage $message
     * @param Callable $next
     * @param BotMan $bot
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $messageText = $message->getText();
        $strippedText = $this->stripFormatting($messageText);

        $message->setText($strippedText);

        return $next($message);
    }

    /**
     * Removes `<` and `>` characters surrounding spotify URLs.
     *
     * @param string $text The complete message text.
     * @return string The stripped text.
     */
    private function stripFormatting(string $text) : string
    {
        return preg_replace('/(<)(https.*)(>)/', '${2}', $text);
    }
}
