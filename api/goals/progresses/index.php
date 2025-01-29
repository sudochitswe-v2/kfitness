<?php
require_once '../../../loader.php';


use K\Kfitness\Libs\Database\ProgressesRepository;
use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Helper\CookieChecker;
use K\Kfitness\Libs\Helper\ResponseHandler;

try {
    CookieChecker::checkCookie();
    $repo = new ProgressesRepository(new MySQL());
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
        if (isset($_GET['goal_id'])) {
            $id = $_GET['goal_id'];
            $data = $repo->progresses($id);
            ResponseHandler::handleResponse(200, $data);
         }else {
            $data = $repo->getAll();
            ResponseHandler::handleResponse(200, $data);
        }
    }
} catch (\Throwable $th) {
    ResponseHandler::handleException($th);
}
