<?php
declare(strict_types=1);

final class Ficha
{
    public static function listFor(int $userId, string $filter, string $search): array
    {
        $sql = "SELECT f.*, COUNT(fe.id) AS quantidade_exercicios
                FROM fichas f LEFT JOIN ficha_exercicios fe ON fe.ficha_id = f.id
                WHERE f.usuario_id = :uid";
        $params = ['uid' => $userId];
        if ($filter === 'ativas') $sql .= " AND f.status = 'ativa'";
        if ($filter === 'arquivadas') $sql .= " AND f.status = 'arquivada'";
        if ($search !== '') {
            $sql .= ' AND f.nome LIKE :busca';
            $params['busca'] = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';
        }
        $sql .= ' GROUP BY f.id ORDER BY f.status ASC, f.atualizado_em DESC, f.id DESC';
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function find(int $id, int $userId, bool $lock = false): ?array
    {
        $st = db()->prepare('SELECT * FROM fichas WHERE id = ? AND usuario_id = ?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id, $userId]);
        return $st->fetch() ?: null;
    }

    public static function hasActiveSession(int $id, int $userId): bool
    {
        $st = db()->prepare("SELECT COUNT(*) FROM sessoes WHERE ficha_id = ? AND usuario_id = ? AND status = 'em_andamento'");
        $st->execute([$id, $userId]);
        return (int) $st->fetchColumn() > 0;
    }

    public static function hasSessions(int $id, int $userId): bool
    {
        $st = db()->prepare('SELECT COUNT(*) FROM sessoes WHERE ficha_id = ? AND usuario_id = ?');
        $st->execute([$id, $userId]);
        return (int) $st->fetchColumn() > 0;
    }

    public static function create(int $userId, string $name, ?string $description, ?string $group): int
    {
        $st = db()->prepare('INSERT INTO fichas (usuario_id, nome, descricao, grupo_muscular) VALUES (?, ?, ?, ?)');
        $st->execute([$userId, $name, $description, $group]);
        return (int) db()->lastInsertId();
    }
}
