<?php
declare(strict_types=1);

final class Exercicio
{
    public const GROUPS = ['Peito', 'Costas', 'Pernas', 'Ombros', 'Bracos', 'Abdomen', 'Outros'];

    public static function available(int $userId): array
    {
        $st = db()->prepare("SELECT id, nome, grupo_muscular, usuario_id FROM exercicios
            WHERE status = 'ativo' AND (usuario_id IS NULL OR usuario_id = ?)
            ORDER BY grupo_muscular, nome, id");
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public static function accessible(int $id, int $userId): bool
    {
        $st = db()->prepare("SELECT COUNT(*) FROM exercicios
            WHERE id = ? AND status = 'ativo' AND (usuario_id IS NULL OR usuario_id = ?)");
        $st->execute([$id, $userId]);
        return (int) $st->fetchColumn() > 0;
    }

    public static function browse(int $userId, string $search, string $group, string $scope, bool $archived): array
    {
        $sql = "SELECT e.*, (SELECT COUNT(*) FROM ficha_exercicios fe WHERE fe.exercicio_id=e.id) AS uso_em_fichas
                FROM exercicios e WHERE (e.usuario_id IS NULL OR e.usuario_id=:uid)";
        $params = ['uid' => $userId];
        if ($archived) {
            $sql .= " AND e.status='arquivado' AND e.usuario_id=:own";
            $params['own'] = $userId;
        } else {
            $sql .= " AND e.status='ativo'";
        }
        if ($scope === 'gerais') $sql .= ' AND e.usuario_id IS NULL';
        if ($scope === 'meus') { $sql .= ' AND e.usuario_id=:personal'; $params['personal'] = $userId; }
        if ($group !== '') { $sql .= ' AND e.grupo_muscular=:grp'; $params['grp'] = $group; }
        if ($search !== '') {
            $sql .= ' AND e.nome LIKE :search';
            $params['search'] = '%' . str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $search) . '%';
        }
        $sql .= ' ORDER BY e.grupo_muscular, e.nome, e.id LIMIT 300';
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function visible(int $id, int $userId, bool $lock = false): ?array
    {
        $st = db()->prepare('SELECT * FROM exercicios WHERE id=? AND (usuario_id IS NULL OR usuario_id=?)' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id, $userId]);
        return $st->fetch() ?: null;
    }

    public static function own(int $id, int $userId, bool $lock = false): ?array
    {
        $st = db()->prepare('SELECT * FROM exercicios WHERE id=? AND usuario_id=?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id, $userId]);
        return $st->fetch() ?: null;
    }

   
    public static function findEquivalent(int $userId, string $name, string $group, ?int $exceptId = null): ?array
    {
        $sql = 'SELECT id, nome, status FROM exercicios
            WHERE usuario_id = ?
              AND LOWER(TRIM(nome)) = LOWER(TRIM(?))
              AND grupo_muscular = ?';

        $params = [$userId, $name, $group];

        if ($exceptId !== null) {
        $sql .= ' AND id <> ?';
        $params[] = $exceptId;
        }

        $sql .= ' ORDER BY id LIMIT 1';

        $st = db()->prepare($sql);
        $st->execute($params);

    return $st->fetch() ?: null;
    }
    public static function create(int $userId, array $v): int
    {
        $st = db()->prepare('INSERT INTO exercicios (usuario_id,nome,grupo_muscular,descricao,imagem_url,video_url) VALUES (?,?,?,?,?,?)');
        $st->execute([$userId,$v['nome'],$v['grupo_muscular'],$v['descricao'] ?: null,$v['imagem_url'] ?: null,$v['video_url'] ?: null]);
        return (int) db()->lastInsertId();
    }

    public static function update(int $id, int $userId, array $v): void
    {
        $st = db()->prepare('UPDATE exercicios SET nome=?,grupo_muscular=?,descricao=?,imagem_url=?,video_url=? WHERE id=? AND usuario_id=?');
        $st->execute([$v['nome'],$v['grupo_muscular'],$v['descricao'] ?: null,$v['imagem_url'] ?: null,$v['video_url'] ?: null,$id,$userId]);
    }
}
