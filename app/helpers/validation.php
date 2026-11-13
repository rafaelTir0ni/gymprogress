<?php
declare(strict_types=1);
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function routeUrl(string $route): string
{
    return ($_SERVER['SCRIPT_NAME'] ?? '/index.php') . '?route=' . rawurlencode($route);
}
function go(string $route): never
{
    header('Location: ' . routeUrl($route), true, 303);
    exit;
}
function postValue(string $name): string
{
    return isset($_POST[$name]) && is_string($_POST[$name]) ? trim($_POST[$name]) : '';
}
function flash(string $message): void
{
    $_SESSION['flash_notice'] = $message;
}
function takeFlash(): string
{
    $value = $_SESSION['flash_notice'] ?? '';
    unset($_SESSION['flash_notice']);
    return is_string($value) ? $value : '';
}
