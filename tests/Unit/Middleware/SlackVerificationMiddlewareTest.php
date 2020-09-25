<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Middleware\SlackVerificationMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\RequestFactory;

class SlackVerificationMiddlewareTest extends TestCase
{
    public function testMiddlewareInterceptsChallengeRequests(): void
    {
        $subject = new SlackVerificationMiddleware();
        $handler = $this->createMock(RequestHandlerInterface::class);
        $request = (new RequestFactory())->createRequest('POST', 'https://hermes.chatbot');

        $request->getBody()->write(<<<JSON
        {
            "challenge": "asdf"
        }
        JSON);

        $response = $subject->process($request, $handler);

        $this->assertEquals('asdf', (string) $response->getBody());
    }

    public function testMiddlewareDefersRequestsNotContainingAChallenge(): void
    {
        $subject = new SlackVerificationMiddleware();
        $handler = $this->createMock(RequestHandlerInterface::class);
        $request = (new RequestFactory())->createRequest('POST', 'https://hermes.chatbot');

        $handler
            ->expects($this->once())
            ->method('handle');

        $subject->process($request, $handler);
    }
}
