<?php

declare(strict_types=1);

namespace App\BotMan;

use BotMan\BotMan\BotMan;
use UnexpectedValueException;

use function class_exists;
use function count;
use function explode;
use function method_exists;
use function sprintf;

class ConfigParser
{
    /** @var array<string, string> */
    protected array $messages;

    /** @var array<string, string> */
    private array $conversations;

    /**
     * @param array<string, string> $messages
     * @param array<string, string> $conversations
     */
    public function __construct(array $messages, array $conversations)
    {
        $this->messages      = $messages;
        $this->conversations = $conversations;
    }

    public function configure(BotMan $bot): void
    {
        foreach ($this->messages as $hears => $reply) {
            $bot->hears($hears, $this->addReply($reply));
        }

        foreach ($this->conversations as $hears => $conversation) {
            $bot->hears($hears, $this->addConversation($conversation));
        }
    }

    private function addReply(string $reply): callable
    {
        return static function (BotMan $bot) use ($reply): void {
            $bot->reply($reply);
        };
    }

    /**
     * Add the Conversation String to the bot.
     *
     * Note: The Conversation string must include the fully qualified class
     * name followed by the `@` symbol and the class method.
     *
     *     App\Conversation\MyConversation@handle
     *
     * @param string $conversation The conversation string
     *
     * @return string $conversation The conversation string.
     */
    private function addConversation(string $conversation): string
    {
        if (! $this->isConversationValid($conversation)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unable to parse the given Conversation string: %s',
                    $conversation
                )
            );
        }

        return $conversation;
    }

    /**
     * Check if the given conversation is valid.
     *
     * @param string $reply The conversation string.
     *
     * @return true If the conversation is formatted correctly and both the
     *              conversation/method exist.
     */
    private function isConversationValid(string $reply): bool
    {
        $conversationParts = explode('@', $reply);

        if (count($conversationParts) !== 2) {
            return false;
        }

        [$class, $method] = $conversationParts;

        if ($class === null || $method === null) {
            return false;
        }

        if (! class_exists($class, true)) {
            return false;
        }

        return method_exists($class, $method);
    }
}
