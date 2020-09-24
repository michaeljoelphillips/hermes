<?php

declare(strict_types=1);

namespace App\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Slack\SlackDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

use function array_pop;
use function json_decode;
use function sprintf;

class TwitchWebhookController
{
    private BotMan $botman;
    private LoggerInterface $logger;

    public function __construct(BotMan $botman, LoggerInterface $logger)
    {
        $this->botman = $botman;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->userIsLive($request)) {
            $user = $this->getUsernameFromRequest($request);

            $this->botman->say(sprintf('%s is streaming on Twitch!', $user), '#groomsmen', SlackDriver::class);
        }

        return $response->withStatus(200);
    }

    private function userIsLive(ServerRequestInterface $request): bool
    {
        $request = json_decode((string) $request->getBody());

        return empty($request->data) === false;
    }

    private function getUsernameFromRequest(ServerRequestInterface $request): string
    {
        $request = json_decode((string) $request->getBody());

        return array_pop($request->data)->user_name;
    }
}
