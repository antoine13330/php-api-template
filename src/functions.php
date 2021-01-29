<?php

function send($content, $statusCode = 200)
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    return json_encode($content, JSON_INVALID_UTF8_IGNORE);
}
