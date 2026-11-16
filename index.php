<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$routes = require __DIR__ . '/app/routes.php';
$page = $_GET['page'] ?? 'forum';
if (!is_string($page) || !isset($routes[$page])) {
    http_response_code(404);
    set_flash('error', 'The requested page could not be found.');
    $page = 'forum';
}

[$controllerClass, $action] = $routes[$page];
$controller = new $controllerClass($forumStore);
$controller->$action();
