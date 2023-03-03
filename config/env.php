<?php

return function (array $settings): array {
    $settings['jwt']['private_key'] = $_ENV['PRIVATE_KEY'];
    $settings['jwt']['public_key'] = $_ENV['PUBLIC_KEY'];
    $settings['db']['host'] = $_ENV['DB_HOST'];
    $settings['db']['database'] = $_ENV['DB_NAME'];
    $settings['db']['username'] = $_ENV['DB_USER'];
    $settings['db']['password'] = $_ENV['DB_PASS'];

    return $settings;
};