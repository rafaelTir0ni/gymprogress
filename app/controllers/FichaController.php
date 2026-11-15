<?php
declare(strict_types=1);

final class FichaController
{
    private const GROUPS = ['Peito', 'Costas', 'Pernas', 'Ombros', 'Bracos', 'Abdomen', 'Outros'];

    public function index(): void
    {
        requireLogin();
        $filter = isset($_GET['filtro']) && is_string($_GET['filtro']) ? $_GET['filtro'] : 'todas';
        if (!in_array($filter, ['todas', 'ativas', 'arquivadas'], true)) $filter = 'todas';
        $search = isset($_GET['busca']) && is_string($_GET['busca']) ? trim($_GET['busca']) : '';
        $search = mb_substr($search, 0, 120);
        $fichas = Ficha::listFor(currentUserId(), $filter, $search);
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/fichas/index.php';
    }

    public function create(): void
    {
        requireLogin();
        $error = '';
        $values = ['nome' => '', 'descricao' => '', 'grupo_muscular' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $values = $this->formValues();
            $error = $this->validateForm($values);
            if ($error === '') {
                $id = Ficha::create(currentUserId(), $values['nome'], $values['descricao'] ?: null, $values['grupo_muscular'] ?: null);
                flash('Ficha criada. Agora você pode adicionar exercícios.');
                $this->redirectDetails($id);
            }
        }
        require dirname(__DIR__) . '/views/fichas/criar.php';
    }

    public function details(): void
    {
        requireLogin();
        $id = $this->getId();
        $ficha = $this->ownFicha($id);
        $items = FichaExercicio::listFor($id);
        $available = Exercicio::available(currentUserId());
        $hasActiveSession = Ficha::hasActiveSession($id, currentUserId());
        $hasSessions = Ficha::hasSessions($id, currentUserId());
        $notice = takeFlash();
        require dirname(__DIR__) . '/views/fichas/detalhes.php';
    }

    public function edit(): void
    {
        requireLogin();
        $id = $this->getId();
        $ficha = $this->ownFicha($id);
        $values = ['nome' => $ficha['nome'], 'descricao' => $ficha['descricao'] ?? '', 'grupo_muscular' => $ficha['grupo_muscular'] ?? ''];
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $values = $this->formValues();
            $error = $this->validateForm($values);
            if ($error === '') {
                $this->transaction(function (array $locked) use ($values, $id): void {
                    $this->assertNoActive($id);
                    $st = db()->prepare('UPDATE fichas SET nome = ?, descricao = ?, grupo_muscular = ? WHERE id = ?');
                    $st->execute([$values['nome'], $values['descricao'] ?: null, $values['grupo_muscular'] ?: null, $id]);
                }, $id);
                flash('Ficha atualizada.');
                $this->redirectDetails($id);
            }
        }
        require dirname(__DIR__) . '/views/fichas/editar.php';
    }

    public function action(): void
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }
        verifyCsrf();
        $id = $this->getId();
        $action = postValue('acao');
        $allowed = ['arquivar', 'reativar', 'excluir', 'adicionar', 'criar_exercicio', 'atualizar_exercicio', 'remover_exercicio', 'subir', 'descer'];
        if (!in_array($action, $allowed, true)) $this->error(400, 'Ação desconhecida.');
        try {
            $deleted = $this->transaction(function (array $ficha) use ($action, $id): bool {
                $userId = currentUserId();
                $this->assertNoActive($id);
                if ($action === 'arquivar' || $action === 'reativar') {
                    $newStatus = $action === 'arquivar' ? 'arquivada' : 'ativa';
                    $st = db()->prepare('UPDATE fichas SET status = ? WHERE id = ?');
                    $st->execute([$newStatus, $id]);
                } elseif ($action === 'excluir') {
                    if (postValue('confirmar') !== 'EXCLUIR') $this->error(422, 'Digite EXCLUIR para confirmar.');
                    if (Ficha::hasSessions($id, $userId)) $this->error(409, 'Esta ficha tem sessões registradas. Arquive-a em vez de excluir.');
                    db()->prepare('DELETE FROM fichas WHERE id = ?')->execute([$id]);
                    return true;
                } else {
                    if ($ficha['status'] !== 'ativa') $this->error(409, 'Reative a ficha para alterar seus exercícios.');
                    if ($action === 'adicionar' || $action === 'criar_exercicio') {
                        [$sets, $min, $max] = $this->prescription();
                        if ($action === 'criar_exercicio') {
                            $name = postValue('nome_exercicio');
                            $group = postValue('grupo_exercicio');
                            $description = postValue('descricao_exercicio');
                            $image = postValue('imagem_url');
                            $video = postValue('video_url');
                            if ($name === '' || mb_strlen($name) > 150 || !in_array($group, self::GROUPS, true) || mb_strlen($description) > 5000) {
                                $this->error(422, 'Informe nome e grupo válidos para o exercício.');
                            }
                            foreach ([$image, $video] as $url) {
                                if ($url !== '' && (strlen($url) > 500 || !filter_var($url, FILTER_VALIDATE_URL) || !in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http','https'], true))) {
                                    $this->error(422, 'Use apenas URLs HTTP/HTTPS válidas de até 500 caracteres.');
                                }
                            }
                            $st = db()->prepare('INSERT INTO exercicios (usuario_id, nome, grupo_muscular, descricao, imagem_url, video_url) VALUES (?, ?, ?, ?, ?, ?)');
                            $st->execute([$userId, $name, $group, $description ?: null, $image ?: null, $video ?: null]);
                            $exerciseId = (int) db()->lastInsertId();
                        } else {
                            $exerciseId = $this->positiveInt($_POST['exercicio_id'] ?? null);
                            if (!Exercicio::accessible($exerciseId, $userId)) $this->error(422, 'Exercício indisponível ou não autorizado.');
                        }
                        FichaExercicio::add($id, $exerciseId, $sets, $min, $max);
                    } else {
                        $itemId = $this->positiveInt($_POST['item_id'] ?? null);
                        $item = FichaExercicio::find($id, $itemId);
                        if (!$item) $this->error(404, 'Exercício não encontrado nesta ficha.');
                        if ($action === 'atualizar_exercicio') {
                            [$sets, $min, $max] = $this->prescription();
                            db()->prepare('UPDATE ficha_exercicios SET series_planejadas = ?, reps_min = ?, reps_max = ? WHERE id = ? AND ficha_id = ?')
                                ->execute([$sets, $min, $max, $itemId, $id]);
                        } elseif ($action === 'remover_exercicio') {
                            db()->prepare('DELETE FROM ficha_exercicios WHERE id = ? AND ficha_id = ?')->execute([$itemId, $id]);
                        } else {
                            $sign = $action === 'subir' ? '<' : '>';
                            $direction = $action === 'subir' ? 'DESC' : 'ASC';
                            $st = db()->prepare("SELECT id, ordem FROM ficha_exercicios WHERE ficha_id = ? AND ordem $sign ? ORDER BY ordem $direction, id $direction LIMIT 1");
                            $st->execute([$id, $item['ordem']]);
                            $other = $st->fetch();
                            if ($other) {
                                db()->prepare('UPDATE ficha_exercicios SET ordem = ? WHERE id = ?')->execute([$other['ordem'], $itemId]);
                                db()->prepare('UPDATE ficha_exercicios SET ordem = ? WHERE id = ?')->execute([$item['ordem'], $other['id']]);
                            }
                        }
                    }
                }
                return false;
            }, $id);
            flash($deleted ? 'Ficha excluída.' : 'Alterações salvas.');
            if ($deleted) go('fichas');
            $this->redirectDetails($id);
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') {
                flash('Não foi possível concluir: exercício duplicado ou ficha referenciada por um treino.');
                $this->redirectDetails($id);
            }
            throw $ex;
        }
    }

    private function transaction(callable $callback, int $id): mixed
    {
        db()->beginTransaction();
        try {
            $ficha = $this->ownFicha($id, true);
            $result = $callback($ficha);
            db()->commit();
            return $result;
        } catch (Throwable $ex) {
            if (db()->inTransaction()) db()->rollBack();
            throw $ex;
        }
    }

    private function getId(): int
    {
        return $this->positiveInt($_GET['id'] ?? $_POST['id'] ?? null);
    }

    private function positiveInt(mixed $value): int
    {
        $value = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($value === false) $this->error(400, 'Identificador inválido.');
        return (int) $value;
    }

    private function ownFicha(int $id, bool $lock = false): array
    {
        $ficha = Ficha::find($id, currentUserId(), $lock);
        if (!$ficha) $this->error(404, 'Ficha não encontrada.');
        return $ficha;
    }

    private function assertNoActive(int $id): void
    {
        if (Ficha::hasActiveSession($id, currentUserId())) $this->error(409, 'Não é possível alterar esta ficha enquanto existir uma sessão em andamento.');
    }

    private function prescription(): array
    {
        $sets = $this->positiveInt($_POST['series'] ?? null);
        $reps = postValue('repeticoes');
        if (!preg_match('/^([0-9]{1,5})(?:\s*[-–]\s*([0-9]{1,5}))?$/u', $reps, $matches)) {
            $this->error(422, 'Use repetições fixas (10) ou intervalo (8-12).');
        }
        $min = (int) $matches[1];
        $max = isset($matches[2]) ? (int) $matches[2] : $min;
        if ($sets > 65535 || $min < 1 || $max < $min || $max > 65535) $this->error(422, 'Séries ou repetições fora dos limites permitidos.');
        return [$sets, $min, $max];
    }

    private function formValues(): array
    {
        return ['nome' => postValue('nome'), 'descricao' => postValue('descricao'), 'grupo_muscular' => postValue('grupo_muscular')];
    }

    private function validateForm(array $v): string
    {
        if ($v['nome'] === '' || mb_strlen($v['nome']) > 120) return 'Informe um nome de até 120 caracteres.';
        if (mb_strlen($v['descricao']) > 5000) return 'A descrição é muito longa.';
        if (mb_strlen($v['grupo_muscular']) > 80) return 'O grupo muscular ultrapassa 80 caracteres.';
        return '';
    }

    private function redirectDetails(int $id): never
    {
        header('Location: ' . routeUrl('fichas-detalhes') . '&id=' . $id, true, 303);
        exit;
    }

    private function error(int $code, string $message): never
    {
        if (db()->inTransaction()) db()->rollBack();
        http_response_code($code);
        exit(e($message));
    }
}
