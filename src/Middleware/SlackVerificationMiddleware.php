<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

use function json_decode;

class SlackVerificationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody(), true);

        if (isset($requestBody['challenge']) === true) {
            $response     = (new ResponseFactory())->createResponse(200);
            $responseBody = $response->getBody();

            $responseBody->write($requestBody['challenge']);

            return $response;
        }

        return $handler->handle($request);
    }
}
