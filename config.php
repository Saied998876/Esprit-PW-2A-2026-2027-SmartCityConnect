<?php

session_start();

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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
