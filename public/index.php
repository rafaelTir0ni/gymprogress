<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/helpers/validation.php';
require_once dirname(__DIR__) . '/app/helpers/auth.php';
require_once dirname(__DIR__) . '/app/helpers/csrf.php';
require_once dirname(__DIR__) . '/app/models/Usuario.php';
require_once dirname(__DIR__) . '/app/controllers/AuthController.php';
require_once dirname(__DIR__) . '/app/models/Ficha.php';
require_once dirname(__DIR__) . '/app/models/FichaExercicio.php';
require_once dirname(__DIR__) . '/app/models/Exercicio.php';
require_once dirname(__DIR__) . '/app/controllers/FichaController.php';
require_once dirname(__DIR__) . '/app/controllers/BibliotecaController.php';

// Em produção use HTTPS; em ambiente local o cookie Secure precisa acompanhar a conexão.
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $isHttps ? '1' : '0');
session_name('gymprogress_session');
session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax']);
session_start();
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header('Cache-Control: private, no-store, max-age=0');

$route = $_GET['route'] ?? 'login';
if (!is_string($route)) { http_response_code(404); exit('Página não encontrada.'); }
$controller = new AuthController();
try {
    switch ($route) {
        case 'login':
            $controller->login(); break;
        case 'cadastro':
            $controller->register(); break;
        case 'logout':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método inválido.'); }
            $controller->logout(); break;
        case 'fichas':
            (new FichaController())->index(); break;
        case 'fichas-criar':
            (new FichaController())->create(); break;
        case 'fichas-detalhes':
            (new FichaController())->details(); break;
        case 'fichas-editar':
            (new FichaController())->edit(); break;
        case 'fichas-acao':
            (new FichaController())->action(); break;
        case 'biblioteca':
            (new BibliotecaController())->index(); break;
        case 'biblioteca-detalhes':
            (new BibliotecaController())->details(); break;
        case 'biblioteca-criar':
            (new BibliotecaController())->create(); break;
        case 'biblioteca-editar':
            (new BibliotecaController())->edit(); break;
        case 'biblioteca-acao':
            (new BibliotecaController())->action(); break;
        case 'dashboard':
            requireLogin();
            require dirname(__DIR__) . '/app/views/dashboard/index.php'; break;
        default:
            http_response_code(404);
            require dirname(__DIR__) . '/app/views/auth/404.php';
    }
} catch (PDOException $exception) {
    error_log('[GymProgress DB] ' . $exception->getMessage());
    http_response_code(503);
    echo 'Não foi possível acessar o serviço agora. Verifique a conexão do banco de dados.';
}
