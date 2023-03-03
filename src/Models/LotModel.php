<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Psr\Container\ContainerInterface;

class LotModel
{
    private PDO $connection;

    public function __construct(protected ContainerInterface $container)
    {
        $this->connection = $this->container->get('db');
    }

    public function add(array $lot): bool
    {
        $stmt = $this->connection->prepare('CALL INSERT_EMISSIONS_LOT(
            :title,
            :description,
            :coordinates,
            :owner_record_id
        );');
        $stmt->bindParam(':title', $lot['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $lot['description'], PDO::PARAM_STR);
        $stmt->bindParam(':coordinates', $lot['coordinates'], PDO::PARAM_STR);
        $stmt->bindParam(':owner_record_id', $lot['owner_record_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update(array $lot): bool
    {
        $stmt = $this->connection->prepare('CALL UPDATE_EMISSIONS_LOT(
            :title,
            :description,
            :coordinates,
            :owner_record_id
        );');
        $stmt->bindParam(':title', $lot['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $lot['description'], PDO::PARAM_STR);
        $stmt->bindParam(':coordinates', $lot['coordinates'], PDO::PARAM_STR);
        $stmt->bindParam(':owner_record_id', $lot['owner_record_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}