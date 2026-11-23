<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/user.php';

$email = getenv('ADMIN_EMAIL');
$password = getenv('ADMIN_PASSWORD');
if (!$email || !$password) {
    fwrite(STDERR, "Set ADMIN_EMAIL and ADMIN_PASSWORD before running this script.\n");
    exit(1);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12
    || strlen($password) > 72) {
    fwrite(STDERR, "Use a valid email and a password between 12 and 72 characters.\n");
    exit(1);
}

$db = database();
$userModel = new UserModel($db);
$created = $userModel->createInitialAdmin(
    strtolower(trim($email)),
    password_hash($password, PASSWORD_DEFAULT)
);
if (!$created) {
    fwrite(STDERR, "An administrator account already exists.\n");
    exit(1);
}

echo "Administrator account is ready.\n";
