<?php

namespace K\Kfitness\Libs\Helper;

class ResponseHandler
{
    static function handleResponse($statusCode, $data = null)
    {
        http_response_code($statusCode);
        if ($data !== null) {
            echo json_encode($data);
        }
        exit;
    }
    static function handleException($th)
    {
        self::handleResponse(500, ['error' => $th->getMessage()]);
    }
}
