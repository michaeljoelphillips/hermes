<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Spotify\SessionStorage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpotifyWebAPI\Session;

class SpotifyAuthorizeController
{
    private Session $session;
    private SessionStorage $storage;

    public function __construct(Session $session, SessionStorage $storage)
    {
        $this->session = $session;
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $code = $request->getQueryParams()['code'];

        $this->session->requestAccessToken($code);
        $this->storage->setSession($this->session);

        return $response->withStatus(200);
    }
}
