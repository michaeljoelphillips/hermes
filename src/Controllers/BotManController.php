<?php

declare(strict_types=1);

namespace App\Controllers;

use BotMan\BotMan\BotMan;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BotManController
{
    protected BotMan $botman;

    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function chat(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->botman->listen();

        return $response->withStatus(200);
    }
}
