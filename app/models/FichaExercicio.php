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

    public static function add(int $fichaId, int $exerciseId, int $sets, int $min, int $max): void
    {
        $st = db()->prepare('INSERT INTO ficha_exercicios
            (ficha_id, exercicio_id, ordem, series_planejadas, reps_min, reps_max)
            VALUES (?, ?, ?, ?, ?, ?)');
        $st->execute([$fichaId, $exerciseId, self::nextOrder($fichaId), $sets, $min, $max]);
    }
}
