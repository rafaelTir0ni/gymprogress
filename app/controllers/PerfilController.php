<?php
declare(strict_types=1);

final class PerfilController
{
    private function account(bool $lock = false): array
    {
        $st = db()->prepare('SELECT id, nome, email, senha_hash, timezone, criado_em FROM usuarios WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([currentUserId()]);
        $account = $st->fetch();
        if (!$account) { logoutUser(); go('login'); }
        return $account;
    }

    public function index(): void
    {
        requireLogin();
        $account = $this->account();
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/perfil/index.php';
    }

    public function edit(): void
    {
        requireLogin();
        $account = $this->account();
        $error = '';
        $values = ['nome' => $account['nome'], 'email' => $account['email']];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $values['nome'] = preg_replace('/\s+/u', ' ', postValue('nome')) ?? '';
            $values['email'] = strtolower(postValue('email'));
            if ($values['nome'] === '' || mb_strlen($values['nome']) > 120) {
                $error = 'Informe um nome de até 120 caracteres.';
            } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || strlen($values['email']) > 190) {
                $error = 'Informe um e-mail válido.';
            } else {
                try {
                    $st = db()->prepare('UPDATE usuarios SET nome = ?, email = ? WHERE id = ?');
                    $st->execute([$values['nome'], $values['email'], currentUserId()]);
                    $_SESSION['usuario_nome'] = $values['nome'];
                    session_regenerate_id(true);
                    flash('Dados do perfil atualizados.');
                    go('perfil');
                } catch (PDOException $ex) {
                    if ($ex->getCode() === '23000') $error = 'Este e-mail já está cadastrado em outra conta.';
                    else throw $ex;
                }
            }
        }
        require dirname(__DIR__) . '/views/perfil/editar.php';
    }

    public function security(): void
    {
        requireLogin();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $current = isset($_POST['senha_atual']) && is_string($_POST['senha_atual']) ? $_POST['senha_atual'] : '';
            $next = isset($_POST['nova_senha']) && is_string($_POST['nova_senha']) ? $_POST['nova_senha'] : '';
            $confirm = isset($_POST['confirmar_senha']) && is_string($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';
            if (strlen($next) < 8 || strlen($next) > 1024) $error = 'A nova senha deve ter pelo menos 8 caracteres.';
            elseif (!hash_equals($next, $confirm)) $error = 'A confirmação não corresponde à nova senha.';
            else {
                $pdo = db();
                $pdo->beginTransaction();
                try {
                    $account = $this->account(true);
                    if (!password_verify($current, $account['senha_hash'])) {
                        $error = 'Senha atual incorreta.';
                        $pdo->rollBack();
                    } elseif (hash_equals($current, $next)) {
                        $error = 'Escolha uma senha diferente da atual.';
                        $pdo->rollBack();
                    } else {
                        $st = $pdo->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
                        $st->execute([password_hash($next, PASSWORD_DEFAULT), currentUserId()]);
                        $pdo->commit();
                        logoutUser();
                        // A sessão atual é encerrada após a alteração sensível.
                        // Revogação de outras sessões exige infraestrutura adicional.
                        session_name('gymprogress_session');
                        session_start();
                        flash('Senha alterada. Entre novamente com a nova senha.');
                        go('login');
                    }
                } catch (Throwable $ex) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    throw $ex;
                }
            }
        }
        require dirname(__DIR__) . '/views/perfil/seguranca.php';
    }

    public function settings(): void
    {
        requireLogin();
        $account = $this->account();
        $allowed = ['America/Sao_Paulo', 'America/Manaus', 'America/Belem', 'America/Rio_Branco', 'UTC'];
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $timezone = postValue('timezone');
            if (!in_array($timezone, $allowed, true)) $error = 'Selecione um fuso horário válido.';
            else {
                $st = db()->prepare('UPDATE usuarios SET timezone = ? WHERE id = ?');
                $st->execute([$timezone, currentUserId()]);
                flash('Preferências atualizadas.');
                go('perfil-configuracoes');
            }
        }
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/perfil/configuracoes.php';
    }
}
