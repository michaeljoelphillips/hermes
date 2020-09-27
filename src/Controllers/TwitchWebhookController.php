<?php

declare(strict_types=1);

namespace App\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Slack\SlackDriver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

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
            $streamPayload = $request->getParsedBody()['data'][0];
            $title         = $streamPayload['title'];
            $user          = $streamPayload['user_name'];

            $this->botman->say(
                sprintf(
                    <<<MESSAGE
                    %s went live on Twitch!

                    %s

                    https://twitch.tv/%s
                    MESSAGE,
                    $user,
                    $title,
                    $user
                ),
                '#groomsmen',
                SlackDriver::class
            );
        }

        return $response->withStatus(200);
    }

    private function userIsLive(ServerRequestInterface $request): bool
    {
        $streamPayload = $request->getParsedBody()['data'];

        return empty($request->getParsedBody()) === false
            && $streamPayload[0]['type'] === 'live';
    }
}
