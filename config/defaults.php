<?php

error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');


$settings['jwt'] = [
    'issuer' => 'localhost',
    'lifetime' => 14400,
    'private_key' => $_ENV['PRIVATE_KEY'],
    'public_key' => $_ENV['PUBLIC_KEY']
];

return $settings;