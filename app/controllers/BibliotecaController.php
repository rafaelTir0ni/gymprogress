<?php
declare(strict_types=1);

final class BibliotecaController
{
    public function index(): void
    {
        requireLogin();
        $search = mb_substr(trim(is_string($_GET['busca'] ?? null) ? $_GET['busca'] : ''), 0, 120);
        $group = is_string($_GET['grupo'] ?? null) ? $_GET['grupo'] : '';
        if (!in_array($group, Exercicio::GROUPS, true)) $group = '';
        $scope = is_string($_GET['tipo'] ?? null) ? $_GET['tipo'] : 'todos';
        if (!in_array($scope, ['todos','gerais','meus'], true)) $scope = 'todos';
        $archived = isset($_GET['arquivados']) && $_GET['arquivados'] === '1';
        if ($archived) $scope = 'meus';
        $exercicios = Exercicio::browse(currentUserId(), $search, $group, $scope, $archived);
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/biblioteca/index.php';
    }

    public function details(): void
    {
        requireLogin();
        $id = $this->positiveId($_GET['id'] ?? null);
        $exercicio = Exercicio::visible($id, currentUserId());
        if (!$exercicio) $this->fail(404, 'Exercício não encontrado.');
        $isMine = $exercicio['usuario_id'] !== null && (int)$exercicio['usuario_id'] === currentUserId();
        $fichas = $exercicio['status'] === 'ativo' ? $this->eligibleFichas() : [];
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/biblioteca/detalhes.php';
    }

    public function create(): void
    {
        requireLogin();
        $values = $this->values();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $error = $this->validate($values);
            if ($error === '') {
                $existing = Exercicio::findEquivalent(currentUserId(), $values['nome'], $values['grupo_muscular']);
                if ($existing) {
                    $error = 'Já existe um exercício com esse nome e grupo muscular na sua Biblioteca (ID ' . $existing['id'] . '). Edite ou reative o existente.';
                }
            }
            if ($error === '') {
                $id = Exercicio::create(currentUserId(), $values);
                flash('Exercício personalizado criado na Biblioteca.');
                $this->redirectDetails($id);
            }
        }
        require dirname(__DIR__) . '/views/biblioteca/formulario.php';
    }

    public function edit(): void
    {
        requireLogin();
        $id = $this->positiveId($_GET['id'] ?? null);
        $own = Exercicio::own($id, currentUserId());
        if (!$own) $this->fail(404, 'Você só pode editar seus exercícios personalizados.');
        if ($own['status'] !== 'ativo') $this->fail(409, 'Reative o exercício para editá-lo.');
        $values = $_SERVER['REQUEST_METHOD'] === 'POST' ? $this->values() : array_map(fn($v) => is_string($v) ? $v : '', array_intersect_key($own, array_flip(['nome','grupo_muscular','descricao','imagem_url','video_url'])));
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $error = $this->validate($values);
            if ($error === '') {
                $existing = Exercicio::findEquivalent(currentUserId(), $values['nome'], $values['grupo_muscular'], $id);
                if ($existing) {
                    $error = 'Outro exercício (ID ' . $existing['id'] . ') já usa esse nome e grupo muscular.';
                }
            }
            if ($error === '') {
                Exercicio::update($id, currentUserId(), $values);
                flash('Exercício atualizado. Treinos finalizados mantêm seus snapshots.');
                $this->redirectDetails($id);
            }
        }
        require dirname(__DIR__) . '/views/biblioteca/formulario.php';
    }

    public function action(): void
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->fail(405, 'Método não permitido.');
        verifyCsrf();
        $id = $this->positiveId($_POST['id'] ?? null);
        $action = postValue('acao');
        if (!in_array($action, ['arquivar','reativar','adicionar'], true)) $this->fail(400, 'Ação inválida.');
        if ($action === 'arquivar' || $action === 'reativar') {
            db()->beginTransaction();
            try {
                $own = Exercicio::own($id, currentUserId(), true);
                if (!$own) $this->fail(404, 'Exercício personalizado não encontrado.');
                $expected = $action === 'arquivar' ? 'ativo' : 'arquivado';
                $new = $action === 'arquivar' ? 'arquivado' : 'ativo';
                if ($own['status'] !== $expected) $this->fail(409, 'O estado do exercício mudou. Atualize a página.');
                db()->prepare('UPDATE exercicios SET status=? WHERE id=? AND usuario_id=?')->execute([$new,$id,currentUserId()]);
                db()->commit();
                flash($new === 'ativo' ? 'Exercício reativado.' : 'Exercício arquivado. Os vínculos existentes foram preservados.');
                $this->redirectDetails($id);
            } catch (Throwable $ex) {
                if (db()->inTransaction()) db()->rollBack();
                throw $ex;
            }
        }

        $fichaId = $this->positiveId($_POST['ficha_id'] ?? null);
        [$sets,$min,$max] = $this->prescription();
        if (postValue('confirmar') !== '1') $this->fail(422, 'Confirme a inclusão na ficha.');
        db()->beginTransaction();
        try {
            $ficha = Ficha::find($fichaId, currentUserId(), true);
            if (!$ficha || $ficha['status'] !== 'ativa') $this->fail(409, 'Selecione uma ficha ativa pertencente à sua conta.');
            if (Ficha::hasActiveSession($fichaId, currentUserId())) $this->fail(409, 'Não é possível alterar uma ficha com sessão em andamento.');
            // Lock exercise alongside the destination fiche so archive cannot race with add.
            $exercise = Exercicio::visible($id, currentUserId(), true);
            if (!$exercise || $exercise['status'] !== 'ativo') $this->fail(409, 'Exercício indisponível ou não autorizado.');
            FichaExercicio::add($fichaId, $id, $sets, $min, $max);
            db()->commit();
            flash('Exercício adicionado à ficha.');
            header('Location: ' . routeUrl('fichas-detalhes') . '&id=' . $fichaId, true, 303);
            exit;
        } catch (DomainException $ex) {
            if (db()->inTransaction()) db()->rollBack();
            flash($ex->getMessage());
            $this->redirectDetails($id);
        } catch (PDOException $ex) {
            if (db()->inTransaction()) db()->rollBack();
            if ($ex->getCode() === '23000') {
                flash('O exercício já está nesta ficha. Não foi duplicado.');
                $this->redirectDetails($id);
            }
            throw $ex;
        } catch (Throwable $ex) {
            if (db()->inTransaction()) db()->rollBack();
            throw $ex;
        }
    }

    private function eligibleFichas(): array
    {
        $st = db()->prepare("SELECT f.id,f.nome, COUNT(fe.id) AS exercicios
            FROM fichas f LEFT JOIN ficha_exercicios fe ON fe.ficha_id=f.id
            WHERE f.usuario_id=? AND f.status='ativa'
              AND NOT EXISTS (SELECT 1 FROM sessoes s WHERE s.ficha_id=f.id AND s.usuario_id=f.usuario_id AND s.status='em_andamento')
            GROUP BY f.id, f.nome ORDER BY f.nome,f.id");
        $st->execute([currentUserId()]);
        return $st->fetchAll();
    }

    private function values(): array
    {
        return ['nome'=>postValue('nome'),'grupo_muscular'=>postValue('grupo_muscular'),
            'descricao'=>postValue('descricao'),'imagem_url'=>postValue('imagem_url'),'video_url'=>postValue('video_url')];
    }

    private function validate(array $v): string
    {
        if ($v['nome'] === '' || mb_strlen($v['nome']) > 150) return 'Informe um nome de até 150 caracteres.';
        if (!in_array($v['grupo_muscular'], Exercicio::GROUPS, true)) return 'Selecione um grupo muscular válido.';
        if (mb_strlen($v['descricao']) > 5000) return 'Descrição muito longa (máximo de 5000 caracteres).';
        foreach (['imagem_url','video_url'] as $field) {
            if ($v[$field] !== '' && (strlen($v[$field]) > 500 || !filter_var($v[$field], FILTER_VALIDATE_URL) || !in_array(strtolower((string)parse_url($v[$field],PHP_URL_SCHEME)), ['http','https'], true))) {
                return 'Informe URLs HTTP/HTTPS válidas com até 500 caracteres.';
            }
        }
        return '';
    }

    private function prescription(): array
    {
        $sets = $this->positiveId($_POST['series'] ?? null);
        $reps = postValue('repeticoes');
        if (!preg_match('/^([0-9]{1,5})(?:\s*[-–]\s*([0-9]{1,5}))?$/u', $reps, $m)) $this->fail(422, 'Informe repetições fixas (10) ou intervalo (8-12).');
        $min = (int)$m[1]; $max = isset($m[2]) ? (int)$m[2] : $min;
        if ($sets > 65535 || $min < 1 || $max < $min || $max > 65535) $this->fail(422, 'Séries ou repetições fora dos limites.');
        return [$sets,$min,$max];
    }

    private function positiveId(mixed $value): int
    {
        $id = filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if ($id === false) $this->fail(400, 'Identificador inválido.');
        return (int)$id;
    }

    private function redirectDetails(int $id): never
    {
        header('Location: ' . routeUrl('biblioteca-detalhes') . '&id=' . $id, true, 303);
        exit;
    }

    private function fail(int $code, string $message): never
    {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code($code);
        exit(e($message));
    }
}
