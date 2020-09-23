<?php

declare(strict_types=1);

namespace App\Twitch;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

use function array_merge;
use function array_pop;
use function json_decode;
use function json_encode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class Client
{
    private const ONE_WEEK       = 604800;
    private const GRANT_TYPE     = 'client_credentials';
    private const TOKEN_ENDPOINT = 'id.twitch.tv/oauth2/token';

    private Guzzle $guzzle;

    private TokenStorage $tokenStorage;

    private string $clientId;

    private string $clientSecret;

    public function __construct(Guzzle $guzzle, TokenStorage $tokenStorage, string $clientId, string $clientSecret)
    {
        $this->guzzle       = $guzzle;
        $this->tokenStorage = $tokenStorage;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function subscribe(string $user, string $webhook): void
    {
        $token = $this->getToken();
        $user  = $this->getUserIdByName($user, $token);

        $body = json_encode(
            [
                'hub.mode' => 'subscribe',
                'hub.callback' => $webhook,
                'hub.lease_seconds' => self::ONE_WEEK,
                'hub.topic' => sprintf('https://api.twitch.tv/helix/streams?user_id=%s', $user),
            ],
            JSON_THROW_ON_ERROR
        );

        $request = new Request(
            'POST',
            'https://api.twitch.tv/helix/webhooks/hub',
            array_merge($this->authHeaders(), ['Content-Type' => 'application/json']),
            $body
        );

        $this->guzzle->send($request);
    }

    private function getUserIdByName(string $user, AccessToken $token): string
    {
        $request  = new Request('GET', sprintf('https://api.twitch.tv/helix/users?login=%s', $user), $this->authHeaders());
        $response = $this->guzzle->send($request);
        $response = json_decode((string) $response->getBody());

        return array_pop($response->data)->id;
    }

    /**
     * @return array<string, string>
     */
    private function authHeaders(): array
    {
        $token = $this->tokenStorage->getToken();

        return [
            'Client-ID' => $this->clientId,
            'Authorization' => (string) $token,
        ];
    }

    private function getToken(): AccessToken
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            $token = $this->refreshToken();
        }

        if ($token->hasExpired() === true) {
            $token = $this->refreshToken();
        }

        return $token;
    }

    private function refreshToken(): AccessToken
    {
        $uri = new Uri(sprintf(
            'https://%s?client_id=%s&client_secret=%s&grant_type=%s',
            self::TOKEN_ENDPOINT,
            $this->clientId,
            $this->clientSecret,
            self::GRANT_TYPE
        ));

        $request      = new Request('POST', $uri);
        $response     = $this->guzzle->send($request);
        $responseBody = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        $token        = AccessToken::fromApiResponse($responseBody);

        $this->tokenStorage->setToken($token);

        return $token;
    }
}
