<?php
declare(strict_types=1);

final class Sessao
{
    public static function active(int $uid): ?array
    {
        $st=db()->prepare("SELECT * FROM sessoes WHERE usuario_id=? AND status='em_andamento' LIMIT 1");
        $st->execute([$uid]); return $st->fetch() ?: null;
    }

    public static function own(int $id,int $uid,bool $lock=false): ?array
    {
        $st=db()->prepare('SELECT * FROM sessoes WHERE id=? AND usuario_id=?'.($lock?' FOR UPDATE':''));
        $st->execute([$id,$uid]); return $st->fetch() ?: null;
    }

    public static function eligible(int $uid): array
    {
        $st=db()->prepare("SELECT f.id,f.nome,f.grupo_muscular,COUNT(fe.id) total
            FROM fichas f JOIN ficha_exercicios fe ON fe.ficha_id=f.id
            WHERE f.usuario_id=? AND f.status='ativa'
            GROUP BY f.id ORDER BY f.nome,f.id");
        $st->execute([$uid]); return $st->fetchAll();
    }

    public static function items(int $sid): array
    {
        $st=db()->prepare('SELECT * FROM sessao_exercicios WHERE sessao_id=? ORDER BY ordem,id');
        $st->execute([$sid]); return $st->fetchAll();
    }

    public static function series(int $sid): array
    {
        $st=db()->prepare('SELECT sr.* FROM series_realizadas sr JOIN sessao_exercicios se ON se.id=sr.sessao_exercicio_id WHERE se.sessao_id=? ORDER BY se.ordem,sr.numero_serie');
        $st->execute([$sid]); $map=[];
        foreach($st->fetchAll() as $r) $map[(int)$r['sessao_exercicio_id']][]=$r;
        return $map;
    }

    public static function item(int $itemId,int $sid): ?array
    {
        $st=db()->prepare('SELECT * FROM sessao_exercicios WHERE id=? AND sessao_id=?');
        $st->execute([$itemId,$sid]); return $st->fetch() ?: null;
    }

    public static function counts(int $sid): array
    {
        $st=db()->prepare("SELECT COUNT(*) total, COALESCE(SUM(concluida=1),0) feitas
            FROM series_realizadas sr JOIN sessao_exercicios se ON se.id=sr.sessao_exercicio_id WHERE se.sessao_id=?");
        $st->execute([$sid]);return $st->fetch();
    }

    public static function graph(int $uid,int $exerciseId): array
    {
        // ISO weeks, dates in UTC; only completed sets in finalized sessions.
        $st=db()->prepare("SELECT MIN(DATE_FORMAT(DATE_SUB(DATE(s.iniciada_em),INTERVAL WEEKDAY(s.iniciada_em) DAY),'%d/%m/%Y')) semana,
            MAX(sr.carga_kg) carga
            FROM sessoes s JOIN sessao_exercicios se ON se.sessao_id=s.id
            JOIN series_realizadas sr ON sr.sessao_exercicio_id=se.id
            WHERE s.usuario_id=? AND s.status='finalizada' AND se.exercicio_id=? AND sr.concluida=1 AND sr.carga_kg IS NOT NULL
            GROUP BY YEARWEEK(s.iniciada_em,3) ORDER BY YEARWEEK(s.iniciada_em,3) DESC LIMIT 12");
        $st->execute([$uid,$exerciseId]);return array_reverse($st->fetchAll());
    }

    public static function recalculateItem(int $itemId,int $sid,bool $manualEarly=false): void
    {
        $item=self::item($itemId,$sid);
        if (!$item) throw new DomainException('Exercício não pertence à sessão.');
        $st=db()->prepare('SELECT COUNT(*) feitas FROM series_realizadas WHERE sessao_exercicio_id=? AND concluida=1');
        $st->execute([$itemId]);$done=(int)$st->fetchColumn();
        $status=$done>= (int)$item['series_planejadas_snapshot']?'concluido':($manualEarly && $done>0?'concluido_antecipadamente':($done?'parcialmente_realizado':'pendente'));
        db()->prepare('UPDATE sessao_exercicios SET status=?,concluido_em=IF(? IN (\'concluido\',\'concluido_antecipadamente\'),UTC_TIMESTAMP(),NULL) WHERE id=? AND sessao_id=?')
            ->execute([$status,$status,$itemId,$sid]);
    }
}
