<?php
declare(strict_types=1);

final class Meta
{
    public static function all(int $userId): array
    {
        $st=db()->prepare('SELECT m.*,e.nome AS exercicio_nome FROM metas m LEFT JOIN exercicios e ON e.id=m.exercicio_id WHERE m.usuario_id=? ORDER BY m.status_base, m.criado_em DESC, m.id DESC');
        $st->execute([$userId]);return $st->fetchAll();
    }

    public static function own(int $userId,int $id,bool $lock=false): ?array
    {
        $st=db()->prepare('SELECT * FROM metas WHERE id=? AND usuario_id=?'.($lock?' FOR UPDATE':''));
        $st->execute([$id,$userId]);return $st->fetch()?:null;
    }
}
