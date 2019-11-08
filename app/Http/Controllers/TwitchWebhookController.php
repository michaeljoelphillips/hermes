<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Slack\SlackDriver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class TwitchWebhookController
{
    private $botman;
    private $logger;

    public function __construct(BotMan $botman, LoggerInterface $logger)
    {
        $this->botman = $botman;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        $this->logger->log('info', $request->getContent());

        if ($this->userIsLive($request)) {
            $user = $this->getUsernameFromRequest($request);

            $this->botman->say(sprintf('%s is streaming on Twitch!', $user), '#thegang', SlackDriver::class);
        }

        return new Response('', 200);
    }

    private function userIsLive(Request $request): bool
    {
        $request = json_decode($request->getContent());

        return false === empty($request->data);
    }

    private function getUsernameFromRequest(Request $request): string
    {
        $request = json_decode($request->getContent());

        return array_pop($request->data)->user_name;
    }
}
