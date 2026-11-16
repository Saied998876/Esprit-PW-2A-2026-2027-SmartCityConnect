<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_base_path(): string
{
    $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    return $directory === '/' || $directory === '.' ? '' : rtrim($directory, '/');
}

function page_url(string $page, array $parameters = []): string
{
    return app_base_path() . '/index.php?' . http_build_query(['page' => $page] + $parameters);
}

function asset_url(string $path): string
{
    return app_base_path() . '/' . ltrim($path, '/');
}

function redirect_to(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): bool
{
    $submitted = $_POST['csrf_token'] ?? '';
    return is_string($submitted) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submitted);
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_array($flash) ? $flash : null;
}

function format_date(string $date): string
{
    $timestamp = strtotime($date);
    return $timestamp === false ? '' : date('M j, Y \a\t g:i A', $timestamp);
}
