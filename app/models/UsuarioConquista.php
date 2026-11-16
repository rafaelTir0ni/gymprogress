<?php
declare(strict_types=1);

final class UsuarioConquista
{
    public static function list(int $uid): array
    {
        $st=db()->prepare('SELECT uc.*,c.nome,c.descricao,c.tipo,e.nome exercicio_nome FROM usuario_conquistas uc JOIN conquistas c ON c.id=uc.conquista_id LEFT JOIN exercicios e ON e.id=uc.exercicio_id WHERE uc.usuario_id=? AND uc.valida=1 ORDER BY uc.conquistada_em DESC,uc.id DESC LIMIT 250');
        $st->execute([$uid]);return $st->fetchAll();
    }

    public static function pending(int $uid): ?array
    {
        $st=db()->prepare('SELECT uc.id,c.nome,c.descricao FROM usuario_conquistas uc JOIN conquistas c ON c.id=uc.conquista_id WHERE uc.usuario_id=? AND uc.valida=1 AND uc.aviso_visualizado_em IS NULL AND uc.aviso_dispensado_em IS NULL ORDER BY uc.conquistada_em DESC,uc.id DESC LIMIT 1');
        $st->execute([$uid]);return $st->fetch()?:null;
    }
}
