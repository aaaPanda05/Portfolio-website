<?php
namespace App\Framework;

class ResponseHelper
{
    public static function json(array|string $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo is_string($data) ? $data : json_encode($data);
    }
}
?>