<?php

\Dotenv\Dotenv::createImmutable(__DIR__. '/../')->safeLoad();

$_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev';

$settings = require_once(__DIR__ . '/defaults.php');

$configFiles = [
    __DIR__ . sprintf('/local.%s.php', $_ENV['APP_ENV']),
    __DIR__ . '/env.php',
    __DIR__ . '/../config/secrets.php',
    __DIR__ . '/../../env.php',
];

foreach ($configFiles as $configFile) {
    if (!file_exists($configFile)) {
        continue;
    }

    $local = require $configFile;
    if (is_callable($local)) {
        $settings = $local($settings);
    }
}

return $settings;