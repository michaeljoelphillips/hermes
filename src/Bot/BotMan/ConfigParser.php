<?php

namespace App\BotMan;

use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class ConfigParser
{
    /** @var array */
    protected $messages;

    /** @var areray */
    private $conversations;

    /**
     * @param array
     */
    public function __construct(array $messages, array $conversations)
    {
        $this->messages = $messages;
        $this->conversations = $conversations;
    }

    /**
     * Configure the bot.
     *
     * @param BotMan $bot
     */
    public function configure(BotMan $bot)
    {
        foreach ($this->messages as $hears => $reply) {
            $bot->hears($hears, $this->addReply($reply));
        }

        foreach ($this->conversations as $hears => $conversation) {
            $bot->hears($hears, $this->addConversation($conversation));
        }
    }

    /**
     * Adds the $reply to the bot.
     *
     * @param string $reply
     */
    private function addReply(string $reply) : Callable
    {
        return function (BotMan $bot) use ($reply) {
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
     * @param string $conversation The conversation string.
     * @return string $conversation The conversation string.
     */
    private function addConversation(string $conversation) : string
    {
        if (!$this->isConversationValid($conversation)) {
            throw new \UnexpectedValueException(sprintf(
                'Unable to parse the given Conversation string: %s',
                $conversation
            ));
        }

        return $conversation;
    }

    /**
     * Check if the given conversation is valid.
     *
     * @param string $reply The conversation string.
     * @return true If the conversation is formatted correctly and both the
     *              conversation/method exist.
     */
    private function isConversationValid(string $reply) : bool
    {
        [$class, $method] = explode('@', $reply);

        if (null == $class || null == $method) {
            return false;
        }

        if (!class_exists($class, true)) {
            return false;
        }

        if (!method_exists($class, $method)) {
            return false;
        }

        return true;
    }
}
