<?php
declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['usuario_id']) && is_int($_SESSION['usuario_id']) && $_SESSION['usuario_id'] > 0;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: ' . routeUrl('login'), true, 303);
        exit;
    }
}

function guestOnly(): void
{
    if (isLoggedIn()) {
        header('Location: ' . routeUrl('dashboard'), true, 303);
        exit;
    }
}

function currentUserId(): int
{
    requireLogin();
    return $_SESSION['usuario_id'];
}

function loginUser(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int) $user['id'];
    $_SESSION['usuario_nome'] = (string) $user['nome'];
    unset($_SESSION['login_attempts'], $_SESSION['login_block_until']);
}

function logoutUser(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $p['path'], 'domain' => $p['domain'],
            'secure' => $p['secure'], 'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}
