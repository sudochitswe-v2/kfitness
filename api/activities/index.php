<?php
require_once '../../loader.php';

use K\Kfitness\Libs\Database\ActivitiesRepository;
use K\Kfitness\Libs\Database\MySQL;
use K\Kfitness\Libs\Helper\CookieChecker;


CookieChecker::checkCookie();
$repo = new ActivitiesRepository(new MySQL());


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $repo->getById($id);
    if ($data) {
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo 'not found';
    }
} else {
    $data =  $repo->getAll();
    echo json_encode($data);
}
