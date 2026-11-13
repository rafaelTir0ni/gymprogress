<?php
declare(strict_types=1);
final class AuthController
{
    public function login(): void
    {
        guestOnly();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $email = strtolower(postValue('email'));
            $password = isset($_POST['senha']) && is_string($_POST['senha']) ? $_POST['senha'] : '';
            if (($_SESSION['login_block_until'] ?? 0) > time()) {
                $error = 'Muitas tentativas. Tente novamente em alguns minutos.';
            } else {
                $user = filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 190
                    ? Usuario::byEmail($email) : null;
                if ($user && password_verify($password, $user['senha_hash'])) {
                    if (password_needs_rehash($user['senha_hash'], PASSWORD_DEFAULT)) {
                        $update = db()->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
                        $update->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
                    }
                    loginUser($user);
                    go('dashboard');
                }
                $_SESSION['login_attempts'] = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['login_block_until'] = time() + 300;
                }
                $error = 'E-mail ou senha incorretos.';
            }
        }
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/auth/login.php';
    }

    public function register(): void
    {
        guestOnly();
        $error = '';
        $name = '';
        $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $name = preg_replace('/\s+/u', ' ', postValue('nome')) ?? '';
            $email = strtolower(postValue('email'));
            $password = isset($_POST['senha']) && is_string($_POST['senha']) ? $_POST['senha'] : '';
            $confirm = isset($_POST['confirmar_senha']) && is_string($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';
            if ($name === '' || mb_strlen($name) > 120) {
                $error = 'Informe um nome de até 120 caracteres.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
                $error = 'Informe um e-mail válido.';
            } elseif (strlen($password) < 8 || strlen($password) > 1024) {
                $error = 'A senha deve ter pelo menos 8 caracteres.';
            } elseif (!hash_equals($password, $confirm)) {
                $error = 'As senhas não coincidem.';
            } else {
                try {
                    Usuario::create($name, $email, $password);
                    flash('Cadastro realizado! Entre com seu e-mail e senha.');
                    go('login');
                } catch (PDOException $ex) {
                    if ($ex->getCode() === '23000') {
                        $error = 'Não foi possível cadastrar este e-mail. Verifique os dados ou tente entrar.';
                    } else {
                        throw $ex;
                    }
                }
            }
        }
        require dirname(__DIR__) . '/views/auth/cadastro.php';
    }

    public function logout(): void
    {
        requireLogin();
        verifyCsrf();
        logoutUser();
        go('login');
    }
}
