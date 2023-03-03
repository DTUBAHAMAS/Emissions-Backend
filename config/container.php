<?php

declare(strict_types=1);

use App\Routing\JwtAuth;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    'settings' => function () {
        return require_once(__DIR__ . '/settings.php');
    },

    App::class => function (ContainerInterface $container) { 
        AppFactory::setContainer($container);
        return AppFactory::create();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    JwtAuth::class => function (ContainerInterface $container) {
        $configuration = $container->get(Configuration::class);

        $jwtSettings = $container->get('settings')['jwt'];
        $issuer = (string) $jwtSettings['issuer'];
        $lifetime = (int) $jwtSettings['lifetime'];

        return new JwtAuth($configuration, $issuer, $lifetime);
    },

    Configuration::class => function (ContainerInterface $container) {
    $jwtSettings = $container->get('settings')['jwt'];

    $privateKey = (string) $jwtSettings['private_key'];
    $publicKey = (string) $jwtSettings['public_key'];

    return Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText($privateKey),
            InMemory::plainText($publicKey)
        );
    },

    'db' => function (ContainerInterface $container) {
        $dbSettings = $container->get('settings')['db'];
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $dsn = "mysql:host={$dbSettings['host']};dbname={$dbSettings['database']};";
        try {
            return new PDO($dsn, $dbSettings['username'], $dbSettings['password'], $options);
        } catch(PDOException $e) {
            header('Content-Type: application/json');
            http_response_code(500);
        }

    }
];