<?php
declare(strict_types=1);

final class FichaExercicio
{
    public static function listFor(int $fichaId): array
    {
        $st = db()->prepare('SELECT fe.*, e.nome AS exercicio_nome, e.grupo_muscular AS exercicio_grupo
            FROM ficha_exercicios fe INNER JOIN exercicios e ON e.id = fe.exercicio_id
            WHERE fe.ficha_id = ? ORDER BY fe.ordem, fe.id');
        $st->execute([$fichaId]);
        return $st->fetchAll();
    }

    public static function find(int $fichaId, int $itemId): ?array
    {
        $st = db()->prepare('SELECT * FROM ficha_exercicios WHERE ficha_id = ? AND id = ?');
        $st->execute([$fichaId, $itemId]);
        return $st->fetch() ?: null;
    }

    public static function nextOrder(int $fichaId): int
    {
        $st = db()->prepare('SELECT COALESCE(MAX(ordem), 0) + 1 FROM ficha_exercicios WHERE ficha_id = ?');
        $st->execute([$fichaId]);
        return (int) $st->fetchColumn();
    }

    /** Compara identidade semântica (nome + grupo), além do id único. */
    public static function hasEquivalent(int $fichaId, string $name, string $group): bool
    {
        $st = db()->prepare('SELECT 1 FROM ficha_exercicios fe
            INNER JOIN exercicios e ON e.id = fe.exercicio_id
            WHERE fe.ficha_id = ?
              AND LOWER(TRIM(e.nome)) = LOWER(TRIM(?))
              AND e.grupo_muscular = ? LIMIT 1');
        $st->execute([$fichaId, $name, $group]);
        return (bool) $st->fetchColumn();
    }

    public static function add(int $fichaId, int $exerciseId, int $sets, int $min, int $max): void
    {
        $exercise = db()->prepare('SELECT nome, grupo_muscular FROM exercicios WHERE id = ?');
        $exercise->execute([$exerciseId]);
        $details = $exercise->fetch();
        if (!$details) throw new RuntimeException('Exercício não encontrado.');
        if (self::hasEquivalent($fichaId, $details['nome'], $details['grupo_muscular'])) {
            throw new DomainException('Esta ficha já contém um exercício com o mesmo nome e grupo muscular.');
        }
        $st = db()->prepare('INSERT INTO ficha_exercicios
            (ficha_id, exercicio_id, ordem, series_planejadas, reps_min, reps_max)
            VALUES (?, ?, ?, ?, ?, ?)');
        $st->execute([$fichaId, $exerciseId, self::nextOrder($fichaId), $sets, $min, $max]);
    }
}
