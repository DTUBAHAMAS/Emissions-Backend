<?php

declare(strict_types = 1);

use App\Controllers\LotController;
use App\Controllers\UserController;
use App\Middleware\JwtAuthMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface  {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT');
    });
    $app->post('/register', [UserController::class, 'register']);
    $app->post('/login', [UserController::class, 'login']);
    $app->group('/api/v1/users', function (RouteCollectorProxy $group) {
        $group->put('/registration', [UserController::class, 'updateRegistration']);
    })->add(JwtAuthMiddleware::class);
    $app->group('/api/v1/lots', function (RouteCollectorProxy $group) {
        $group->post('', [LotController::class, 'create']);
        $group->put('', [LotController::class, 'update']);
    })->add(JwtAuthMiddleware::class);
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};