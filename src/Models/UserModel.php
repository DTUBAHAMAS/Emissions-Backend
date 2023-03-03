<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Psr\Container\ContainerInterface;

class UserModel
{
    private PDO $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('db');
    }

    public function getUserByEmail(string $email): array|false
    {
        $stmt = $this->connection->prepare('CALL GET_EMISSIONS_USER_BY_EMAIL(:email);');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if (!$results = $stmt->fetchAll()) {
            return false;
        }

        if (count($results) === 0) {
            return false;
        }

        return $results[0];
    }

    public function getUserById(string $id): array|false
    {
        $stmt = $this->connection->prepare('CALL GET_EMISSIONS_USER_BY_ID(:id);');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        if (!$results = $stmt->fetchAll()) {
            return false;
        }

        return $results[0];
    }

    public function register(array $user): bool
    {
        $stmt = $this->connection->prepare('CALL INSERT_EMISSIONS_REGISTER_USER(
            :firstname,
            :middlename,
            :lastname,
            :email,
            :password,
            :userRecordId,
            :birthDate,
            :identificationType,
            :identificationNumber,
            :birthCountry,
            :nationality,
            :gender,
            :homePhone,
            :mobilePhone,
            :workPhone,
            :address,
            :addressCont,
            :country,
            :stateProvince,
            :cityTown,
            :postalZip
        )');

        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
        $stmt->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':userRecordId', $user['user_record_id'], PDO::PARAM_STR);
        $stmt->bindParam(':birthDate', $user['date_of_birth'], PDO::PARAM_STR);
        $stmt->bindParam(':identificationType', $user['identification_type'], PDO::PARAM_STR);
        $stmt->bindParam(':identificationNumber', $user['identification_number'], PDO::PARAM_STR);
        $stmt->bindParam(':birthCountry', $user['country_of_birth'], PDO::PARAM_STR);
        $stmt->bindParam(':nationality', $user['nationality'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $user['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':homePhone', $user['home_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':mobilePhone', $user['mobile_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':workPhone', $user['work_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $user['address'], PDO::PARAM_STR);
        $stmt->bindParam(':addressCont', $user['address_cont'], PDO::PARAM_STR);
        $stmt->bindParam(':country', $user['country'], PDO::PARAM_STR);
        $stmt->bindParam(':stateProvince', $user['state_province'], PDO::PARAM_STR);
        $stmt->bindParam(':cityTown', $user['city_town'], PDO::PARAM_STR);
        $stmt->bindParam(':postalZip', $user['postal_zip_code'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function updateRegistration(array $user): bool
    {
        $stmt = $this->connection->prepare('CALL UPDATE_EMISSIONS_REGISTER_USER(
            :firstname,
            :middlename,
            :lastname,
            :password,
            :userRecordId,
            :birthDate,
            :identificationType,
            :identificationNumber,
            :birthCountry,
            :nationality,
            :gender,
            :homePhone,
            :mobilePhone,
            :workPhone,
            :address,
            :addressCont,
            :country,
            :stateProvince,
            :cityTown,
            :postalZip
        );');

        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
        $stmt->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
        $stmt->bindParam('password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':userRecordId', $user['user_record_id'], PDO::PARAM_STR);
        $stmt->bindParam(':birthDate', $user['date_of_birth'], PDO::PARAM_STR);
        $stmt->bindParam(':identificationType', $user['identification_type'], PDO::PARAM_STR);
        $stmt->bindParam(':identificationNumber', $user['identification_number'], PDO::PARAM_STR);
        $stmt->bindParam(':birthCountry', $user['country_of_birth'], PDO::PARAM_STR);
        $stmt->bindParam(':nationality', $user['nationality'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $user['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':homePhone', $user['home_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':mobilePhone', $user['mobile_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':workPhone', $user['work_phone'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $user['address'], PDO::PARAM_STR);
        $stmt->bindParam(':addressCont', $user['address_cont'], PDO::PARAM_STR);
        $stmt->bindParam(':country', $user['country'], PDO::PARAM_STR);
        $stmt->bindParam(':stateProvince', $user['state_province'], PDO::PARAM_STR);
        $stmt->bindParam(':cityTown', $user['city_town'], PDO::PARAM_STR);
        $stmt->bindParam(':postalZip', $user['postal_zip'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}