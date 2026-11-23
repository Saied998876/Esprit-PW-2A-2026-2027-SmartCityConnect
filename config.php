<?php

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self'; img-src 'self' data:; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");

$sessionExpired = isset($_SESSION['last_activity'])
    && time() - $_SESSION['last_activity'] > 1800;
$sessionTooOld = isset($_SESSION['created_at'])
    && time() - $_SESSION['created_at'] > 28800;

if ($sessionExpired || $sessionTooOld) {
    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['created_at'] = time();
    $_SESSION['last_id_change'] = time();
    $_SESSION['messages']['error'] = 'Your session expired. Please sign in again.';
}

if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
    $_SESSION['last_id_change'] = time();
} elseif (!isset($_SESSION['last_id_change'])
    || time() - $_SESSION['last_id_change'] > 900) {
    session_regenerate_id(true);
    $_SESSION['last_id_change'] = time();
}

$_SESSION['last_activity'] = time();

function e($value)
{
    if (!is_scalar($value) && $value !== null) {
        return '';
    }

    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function inputString($form, $name)
{
    return isset($form[$name]) && is_string($form[$name])
        ? $form[$name]
        : '';
}

function inputId($form, $name)
{
    $id = filter_var(inputString($form, $name), FILTER_VALIDATE_INT);

    return $id === false ? 0 : $id;
}

function formatMoney($cents)
{
    return '$' . number_format($cents / 100, 2);
}

function database()
{
    $databasePath = getenv('DONATIONS_DB_PATH');
    if (!$databasePath) {
        $databasePath = __DIR__ . '/data/donations.sqlite';
    }

    $db = new PDO('sqlite:' . $databasePath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->exec('PRAGMA foreign_keys = ON');

    $table = $db->query(
        "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'campaigns'"
    );

    if (!$table->fetch()) {
        $sql = file_get_contents(__DIR__ . '/database.sql');
        if ($sql === false) {
            throw new RuntimeException('Could not read database.sql.');
        }
        $db->exec($sql);
    }

    $usersTable = $db->query(
        "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'users'"
    );
    if (!$usersTable->fetch()) {
        $db->exec(
            "CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL COLLATE NOCASE UNIQUE,
                password_hash TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'citizen'
                    CHECK (role IN ('citizen', 'admin')),
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )"
        );
    }

    return $db;
}

function csrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function checkCsrfToken($token)
{
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function setMessage($name, $message)
{
    $_SESSION['messages'][$name] = $message;
}

function getMessage($name)
{
    if (!isset($_SESSION['messages'][$name])) {
        return null;
    }

    $message = $_SESSION['messages'][$name];
    unset($_SESSION['messages'][$name]);

    return $message;
}
