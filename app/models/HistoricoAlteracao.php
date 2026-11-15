<?php
declare(strict_types=1);

final class HistoricoAlteracao
{
    public static function listForSession(int $sessionId): array
    {
        $st=db()->prepare('SELECT id,tipo_operacao,alterado_em FROM historico_alteracoes WHERE sessao_id=? ORDER BY id DESC LIMIT 50');
        $st->execute([$sessionId]);return $st->fetchAll();
    }

    public static function record(int $sessionId,int $userId,array $before,array $after): void
    {
        $st=db()->prepare('INSERT INTO historico_alteracoes(sessao_id,usuario_id,tipo_operacao,dados_anteriores,dados_novos,alterado_em) VALUES(?,?,?,?,?,UTC_TIMESTAMP())');
        $st->execute([$sessionId,$userId,'correcao_sessao',json_encode($before,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE),json_encode($after,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE)]);
    }
}
