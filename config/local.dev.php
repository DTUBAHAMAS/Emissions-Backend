<?php

use Monolog\Logger;

return function (array $settings): array {
    $settings['error']['display_error_details'] = true;
    $settings['logger']['level'] = Logger::DEBUG;

    return $settings;
};