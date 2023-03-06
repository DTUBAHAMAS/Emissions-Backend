<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Routing\JwtAuth;
use App\Views\JsonRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

const LENGTH_OF_ID = 5;

class UserController
{
    public function __construct(
        protected JsonRenderer $render,
        protected JwtAuth $jwtAuth,
        protected ContainerInterface $container
    )
    {
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user_record_id = bin2hex(random_bytes(LENGTH_OF_ID));

        $data = $request->getParsedBody();

        $user_data = [
            'firstname' => htmlspecialchars($data['firstname']),
            'middlename' => is_null($data['middlename']) ? htmlspecialchars($data['middlename']) : $data['middlename'],
            'lastname' => htmlspecialchars($data['lastname']),
            'email' => htmlspecialchars($data['email']),
            'password' => $data['password'],
            'user_record_id' => $user_record_id,
            'date_of_birth' => htmlspecialchars($data['date_of_birth']),
            'identification_type' => htmlspecialchars($data['identification_type']),
            'identification_number' => htmlspecialchars($data['identification_number']),
            'country_of_birth' => htmlspecialchars($data['country_of_birth']),
            'nationality' => htmlspecialchars($data['nationality']),
            'gender' => htmlspecialchars($data['gender']),
            'home_phone' => htmlspecialchars($data['home_phone']),
            'mobile_phone' => htmlspecialchars($data['mobile_phone']),
            'work_phone' => htmlspecialchars($data['work_phone']),
            'address' => htmlspecialchars($data['address']),
            'address_cont' => htmlspecialchars($data['address_cont']),
            'country' => htmlspecialchars($data['country']),
            'state_province' => htmlspecialchars($data['country']),
            'city_town' => htmlspecialchars($data['city_town']),
            'postal_zip_code' => htmlspecialchars($data['postal_zip_code'])
        ];

        $user = new UserModel($this->container);
        if ($user->register($user_data)) {
            http_response_code(200);
            $message = ["msg" => 'user registration was successful'];
        } else {
            http_response_code(400);
            $message = ["msg" => 'user registration was unsuccessful'];
        }


        return $this->render->json($response, $message);
    }

    public function updateRegistration(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        $user_data = [
            'firstname' => htmlspecialchars($data['firstname']),
            'middlename' => !is_null($data['middlename']) ? htmlspecialchars($data['middlename']) : $data['middlename'],
            'lastname' => htmlspecialchars($data['lastname']),
            'password' => $hashed_password,
            'user_record_id' => htmlspecialchars($data['user_record_id']),
            'date_of_birth' => htmlspecialchars($data['date_of_birth']),
            'identification_type' => htmlspecialchars($data['identification_type']),
            'identification_number' => htmlspecialchars($data['identification_number']),
            'country_of_birth' => htmlspecialchars($data['country_of_birth']),
            'nationality' => htmlspecialchars($data['nationality']),
            'gender' => htmlspecialchars($data['gender']),
            'home_phone' => htmlspecialchars($data['home_phone']),
            'mobile_phone' => htmlspecialchars($data['mobile_phone']),
            'work_phone' => htmlspecialchars($data['work_phone']),
            'address' => htmlspecialchars($data['address']),
            'address_cont' => htmlspecialchars($data['address_cont']),
            'country' => htmlspecialchars($data['country']),
            'state_province' => htmlspecialchars($data['country']),
            'city_town' => htmlspecialchars($data['city_town']),
            'postal_zip_code' => htmlspecialchars($data['postal_zip_code'])
        ];

        $user = new UserModel($this->container);
        if ($user->updateRegistration($user_data)) {
            http_response_code(200);
            $message = ['msg' => 'user registration update wasx successfully'];
        } else {
            http_response_code(400);
            $message = ['msg' => 'user registration update was unsuccessfully'];
        }

        return $this->render->json($response, $message);
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $isValidLogin = false;

        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = $data['password'];

        $user = new UserModel($this->container);
        $result = $user->getUserByEmail($email);

        if (count($result) > 0) {
            $isValidLogin = password_verify($password, $result['password']);
        }

        if (!$isValidLogin) {
            return $this->render->json($response->withStatus(401));
        }

        $token = $this->jwtAuth->createJwt(
            [
                'uid' => $result['user_record_id']
            ]
        );

        $data = [
            'access_token' => $token,
            'token_type'=> 'Bearer',
            'expires_in' => $this->jwtAuth->getLifetime()
        ];

        return $this->render->json($response, $data, JSON_THROW_ON_ERROR);

    }
}