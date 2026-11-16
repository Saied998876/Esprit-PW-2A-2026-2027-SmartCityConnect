<?php

declare(strict_types=1);

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (str_starts_with($requestPath, '/storage/')) {
    http_response_code(404);
    exit;
}

$filePath = __DIR__ . $requestPath;
if ($requestPath !== '/' && is_file($filePath)) {
    return false;
}

require __DIR__ . '/index.php';
