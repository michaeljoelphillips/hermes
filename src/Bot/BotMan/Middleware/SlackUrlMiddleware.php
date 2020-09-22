<?php

declare(strict_types=1);

namespace App\BotMan\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class SlackUrlMiddleware implements Received
{
    /**
     * Strip the `<` and `>` characters from a URL within a message.
     *
     * @param IncomingMessage $message
     * @param callable        $next
     * @param BotMan          $bot
     *
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
     * @param string $text the complete message text
     *
     * @return string the stripped text
     */
    private function stripFormatting($text): string
    {
        return preg_replace('/(<)(https.*)(>)/', '${2}', $text);
    }
}
