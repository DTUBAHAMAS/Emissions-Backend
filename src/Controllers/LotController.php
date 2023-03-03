<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\LotModel;
use App\Routing\JwtAuth;
use App\Views\JsonRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LotController
{
    public function __construct(
        protected JsonRenderer $render,
        protected JwtAuth $jwtAuth,
        protected ContainerInterface $container
    )
    {
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $lot_data = [
            'title' => $data['title'],
            'description' => $data['description'],
            'coordinates' => $data['coordinates'],
            'owner_record_id' => $data['owner_record_id']
        ];

        $lot = new LotModel($this->container);

        if ($lot->add($lot_data)) {
            http_response_code(200);
            $message = ['msg' => 'lot was added successfully'];
        } else {
            http_response_code(400);
            $message = ['msg' => 'lot addition was unsuccessful'];
        }

        return $this->render->json($response, $message);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $lot_data = [
            'title' => $data['title'],
            'description' => $data['description'],
            'coordinates' => $data['coordinates'],
            'owner_record_id' => $data['owner_record_id']
        ];

        $lot = new LotModel($this->container);
        if ($lot->update($lot_data)) {
            $statusCode = 200;
            $message = ['msg' => 'lot was updated successfully'];
        } else {
            $statusCode = 400;
            $message = ['msg' => 'lot update was unsuccessfully'];
        }

        return $this->render->json($response->withStatus($statusCode), $message);
    }
}