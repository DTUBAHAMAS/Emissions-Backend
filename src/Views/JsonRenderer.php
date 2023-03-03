<?php

declare(strict_types=1);

namespace App\Views;

use Psr\Http\Message\ResponseInterface;

final class JsonRenderer
{
    public function json(ResponseInterface $response, array|string $data = null, int $options = 0): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        if (!is_null($data)) {
            $response->getBody()->write((string)json_encode($data, $options));
        }

        return $response;
    }
}