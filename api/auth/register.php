<?php
require_once '../../loader.php';


use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Database\UsersRepository;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usersRepository = new UsersRepository(new MySQL());
    $input = json_decode(file_get_contents('php://input'), true);

    try {

        $existingUser = $usersRepository->findByEmail($input['email']);

        if ($existingUser) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            exit();
        }

        $usersRepository->insert($input);
        http_response_code(200);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode(['error' => $th->getMessage()]);
        //throw $th;
    }
}
