<?php

declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use DI\ContainerBuilder;
use Slim\App;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__. '/container.php');

$container = $containerBuilder->build();

$app = $container->get(App::class);

// Register routes
(require_once(__DIR__ . '/routes.php'))($app);

// Register middleware
(require_once(__DIR__ . '/middleware.php'))($app);

return $app;