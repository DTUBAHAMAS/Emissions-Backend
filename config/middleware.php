<?php

use App\Middleware\JwtClaimMiddleware;
use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    
    $app->addRoutingMiddleware();
    $app->add(JwtClaimMiddleware::class);
    $app->addErrorMiddleware(true, true, true);
};