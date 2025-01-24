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
            $repo->create($input);
            ResponseHandler::handleResponse(200);
            break;

        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $repo->update($input);
            ResponseHandler::handleResponse(200);
            break;

        case 'DELETE':
            ResponseHandler::handleResponse(405, ['error' => 'Method not allowed']);
            break;

        default:
            $data = $repo->getAll();
            ResponseHandler::handleResponse(200, $data);
            break;
    }
} catch (\Throwable $th) {
    ResponseHandler::handleException($th);
}
