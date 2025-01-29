<?php
require_once '../../loader.php';


use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Database\ReportRepository;
use K\Kfitness\Libs\Helper\CookieChecker;
use K\Kfitness\Libs\Helper\ResponseHandler;

try {
    CookieChecker::checkCookie();
    if (isset($_GET['user_id']) && isset($_GET['date'])) {
        $data = [
            'user_id' => $_GET['user_id'],
            'date' => $_GET['date']
        ];
        $goalsRepository = new ReportRepository(new MySQL());
        $response = $goalsRepository->getReport($data);
        ResponseHandler::handleResponse(200, $response);
    } else {
        ResponseHandler::handleException("Invalid request");
    }
} catch (\Throwable $th) {
    ResponseHandler::handleException($th);
}
