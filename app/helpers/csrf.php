<?php
declare(strict_types=1);
function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verifyCsrf(): void
{
    if (!isset($_POST['_csrf']) || !is_string($_POST['_csrf']) ||
        !hash_equals(csrfToken(), $_POST['_csrf'])) {
        http_response_code(419);
        exit('Sua sessão expirou ou o formulário é inválido. Atualize a página e tente novamente.');
    }
}
