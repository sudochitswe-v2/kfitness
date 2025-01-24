<?php
require_once '../../loader.php';

use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Database\UsersRepository;
use K\Kfitness\Libs\Helper\ResponseHandler;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['email']) || !isset($input['password'])) {
            ResponseHandler::handleResponse(400, ['error' => 'Missing required fields']);
            exit;
        }
        $email = $input['email'];
        $password = $input['password'];

        $usersRepository = new UsersRepository(new MySQL());

        $user = $usersRepository->findByEmail($email);
        if ($user) {
            if (password_verify($password, $user->password)) {
                http_response_code(200);

                $key = base64_encode($_ENV['API_COOKIE']);

                $data = [
                    'claim' => $user,
                    'cookie' => $key
                ];
                echo json_encode($data);
            } else {
                ResponseHandler::handleResponse(401, ['error' => 'Invalid password']);
            }
        } else {
            ResponseHandler::handleResponse(404, ['error' => 'User not found']);
        }
    } catch (\Throwable $th) {
        ResponseHandler::handleException($th->getMessage());
    }
}
