<?php
require_once '../../loader.php';


use K\Kfitness\Libs\Database\GoalsRepository;
use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Helper\CookieChecker;
use K\Kfitness\Libs\Helper\ResponseHandler;

try {
    CookieChecker::checkCookie();
    $repo = new GoalsRepository(new MySQL());
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            unset($input['id']);
            $repo->create($input);
            ResponseHandler::handleResponse(200);
            break;

        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $repo->update($input);
            ResponseHandler::handleResponse(200);
            break;

        case 'DELETE':
            $repo->delete($_GET['id']);
            ResponseHandler::handleResponse(200);
            break;

        default:
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $data = $repo->details($id);
                if ($data) {
                    ResponseHandler::handleResponse(200, $data);
                } else {
                    ResponseHandler::handleResponse(404, ['error' => 'Not found']);
                }
            } elseif (isset($_GET['user_id'])) {
                $data =  $repo->userGoals($_GET['user_id']);
                echo json_encode($data);
            } else {
                $data = $repo->getAll();
                ResponseHandler::handleResponse(200, $data);
            }
            break;
    }
} catch (\Throwable $th) {
    ResponseHandler::handleException($th);
}
