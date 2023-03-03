<?php

use Psr\Log\NullLogger;

return function (array $settings): array {
    $settings['error']['display_error_details'] = true;
    $settings['error']['log_errors'] = true;

    $settings['logger'] = [
            'path' => '',
        'level' => 0,
        'test' => new NullLogger()
    ];

    return $settings;
};