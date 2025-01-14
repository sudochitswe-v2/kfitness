<?php
require_once '../../loader.php';

use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Database\UsersRepository;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo 'Missing required fields';
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
            http_response_code(401);
            echo json_encode(['error' => 'Invalid password']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
}
