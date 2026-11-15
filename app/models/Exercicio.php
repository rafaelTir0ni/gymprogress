<?php
declare(strict_types=1);

final class Exercicio
{
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
}
