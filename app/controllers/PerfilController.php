<?php
declare(strict_types=1);

final class PerfilController
{
    private function account(bool $lock = false): array
    {
        $st = db()->prepare('SELECT id, nome, email, senha_hash, timezone, foto_perfil, criado_em FROM usuarios WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''));
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

    /** Entrega a imagem apenas ao próprio titular autenticado. */
    public function photo(): void
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') { http_response_code(405); exit; }
        $account = $this->account();
        $name = profileImageName($account['foto_perfil'] ?? null);
        $file = $name ? profileImageDirectory() . '/' . $name : null;
        if (!$file || !is_file($file)) { http_response_code(404); exit; }
        header('Content-Type: image/jpeg');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: private, no-store, max-age=0');
        header('X-Content-Type-Options: nosniff');
        readfile($file);
        exit;
    }

    /** Upload com validação de conteúdo, limites e reprocessamento via GD. */
    public function uploadPhoto(): void
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
        verifyCsrf();
        $upload = $_FILES['foto'] ?? null;
        if (!is_array($upload) || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash('Selecione uma imagem JPEG, PNG ou WebP de até 3 MB.'); go('perfil');
        }
        $size = $upload['size'] ?? null;
        $temp = $upload['tmp_name'] ?? null;
        if (!is_int($size) || $size < 1 || $size > 3 * 1024 * 1024 || !is_string($temp) || !is_uploaded_file($temp)) {
            flash('Imagem inválida ou acima do limite de 3 MB.'); go('perfil');
        }
        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            flash('Habilite a extensão GD do PHP no Laragon para enviar fotos.'); go('perfil');
        }
        $info = @getimagesize($temp);
        if (!$info || !isset($info[0], $info[1], $info['mime']) || $info[0] < 1 || $info[1] < 1 || $info[0] * $info[1] > 16000000) {
            flash('A imagem possui dimensões inválidas ou muito grandes.'); go('perfil');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temp);
        $types = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/webp' => 'imagecreatefromwebp'];
        if ($mime !== $info['mime'] || !isset($types[$mime]) || !function_exists($types[$mime])) {
            flash('Formato não aceito. Use JPEG, PNG ou WebP.'); go('perfil');
        }
        $source = @$types[$mime]($temp);
        if ($source === false) { flash('Não foi possível ler a imagem.'); go('perfil'); }
        $width = (int) $info[0]; $height = (int) $info[1];
        // Mantém o arquivo final SEMPRE quadrado (512 x 512).
        // O editor no navegador já envia o enquadramento; este recorte central é
        // uma proteção adicional para uploads diretos ou JavaScript desabilitado.
        $crop = min($width, $height);
        $sourceX = intdiv($width - $crop, 2);
        $sourceY = intdiv($height - $crop, 2);
        $target = imagecreatetruecolor(512, 512);
        imagecopyresampled($target, $source, 0, 0, $sourceX, $sourceY, 512, 512, $crop, $crop);
        imagedestroy($source);
        $dir = profileImageDirectory();
        if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
            imagedestroy($target); flash('Não foi possível criar a pasta de fotos.'); go('perfil');
        }
        $filename = 'avatar_' . bin2hex(random_bytes(16)) . '.jpg';
        $path = $dir . '/' . $filename;
        $ok = imagejpeg($target, $path, 85);
        imagedestroy($target);
        if (!$ok) { flash('Não foi possível salvar a foto.'); go('perfil'); }
        @chmod($path, 0600);
        try {
            $pdo = db();
            $pdo->beginTransaction();
            $account = $this->account(true);
            $st = $pdo->prepare('UPDATE usuarios SET foto_perfil = ? WHERE id = ?');
            $st->execute([$filename, currentUserId()]);
            $pdo->commit();
            $old = profileImageName($account['foto_perfil'] ?? null);
            if ($old) @unlink($dir . '/' . $old);
        } catch (Throwable $ex) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            @unlink($path);
            throw $ex;
        }
        flash('Foto de perfil atualizada.'); go('perfil');
    }

    public function removePhoto(): void
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
        verifyCsrf();
        $pdo = db(); $pdo->beginTransaction();
        try {
            $account = $this->account(true);
            $st = $pdo->prepare('UPDATE usuarios SET foto_perfil = NULL WHERE id = ?');
            $st->execute([currentUserId()]);
            $pdo->commit();
        } catch (Throwable $ex) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $ex;
        }
        $old = profileImageName($account['foto_perfil'] ?? null);
        if ($old) @unlink(profileImageDirectory() . '/' . $old);
        flash('Foto de perfil removida.'); go('perfil');
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
