<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Routing\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JwtClaimMiddleware implements MiddlewareInterface
{
    public function __construct(protected JwtAuth $jwtAuth)
    {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $authorization = explode(' ', (string) $request->getHeaderLine('Authorization'));
        $type = $authorization[0] ?? '';
        $credentials = $authorization[1]?? '';

        if ($type !== 'Bearer') {
            return $handler->handle($request);
        }

        $token = $this->jwtAuth->validateToken($credentials);
        if (!$token) {
            $request = $request->withAttribute('token', $token);
            $request = $request->withAttribute('uid', $token->claims()->get('uid'));
        }

        return $handler->handle($request);
    }
} 