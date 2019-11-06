<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use BotMan\Drivers\Slack\SlackDriver;

class TwitchWebhookController
{
    private $botman;

    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function __invoke(Request $request): Response
    {
        return new Response('', 200);
    }
}
