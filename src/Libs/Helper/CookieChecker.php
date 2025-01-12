<?php

namespace K\Kfitness\Libs\Helper;

class CookieChecker
{
    static function checkCookie()
    {
        if (!isset($_SERVER['HTTP_COOKIE'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $token = $_SERVER['HTTP_COOKIE'];
        if (base64_decode($token) != $_ENV['API_COOKIE']) { // Replace with your validation logic
            http_response_code(401);
            echo json_encode(['error' => 'Invalid cookie']);
            exit;
        }
    }
}
