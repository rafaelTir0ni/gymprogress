<?php
declare(strict_types=1);

final class EstatisticaService
{
    public static function timezone(int $userId): DateTimeZone
    {
        $st=db()->prepare('SELECT timezone FROM usuarios WHERE id=?');$st->execute([$userId]);
        try { return new DateTimeZone((string)($st->fetchColumn()?:'America/Sao_Paulo')); }
        catch(Throwable $e) { return new DateTimeZone('America/Sao_Paulo'); }
    }

    public static function localDay(string $utc,DateTimeZone $tz): string
    {
        return (new DateTimeImmutable($utc,new DateTimeZone('UTC')))->setTimezone($tz)->format('Y-m-d');
    }

    public static function weekStart(string $localDay): string
    {
        $d=new DateTimeImmutable($localDay,new DateTimeZone('UTC'));
        return $d->modify('monday this week')->format('Y-m-d');
    }

    public static function records(int $uid): array
    {
        $tz=self::timezone($uid);
        $week=self::weekStart((new DateTimeImmutable('now',$tz))->format('Y-m-d'));
        $personal=[];
        foreach(self::loads($uid) as $r){
            $id=(int)$r['exercicio_id'];$kg=(float)$r['carga'];
            $day=self::localDay($r['finalizada_em'],$tz);
            $personal[$id]['id']=$id;
            $personal[$id]['max']=max($personal[$id]['max']??0,$kg);
            if(self::weekStart($day)===$week)$personal[$id]['semana']=max($personal[$id]['semana']??0,$kg);
        }
        if(!$personal)return [];
        $ids=array_keys($personal);$marks=implode(',',array_fill(0,count($ids),'?'));
        $st=db()->prepare("SELECT id,nome FROM exercicios WHERE id IN ($marks) AND (usuario_id IS NULL OR usuario_id=?) ORDER BY nome");
        $st->execute([...$ids,$uid]);$out=[];
        foreach($st->fetchAll() as $r){$rec=$personal[(int)$r['id']];$rec['nome']=$r['nome'];$out[]=$rec;}
        return array_slice($out,0,50);
    }

    public static function sessions(int $uid): array
    {
        $st=db()->prepare("SELECT id,finalizada_em FROM sessoes WHERE usuario_id=? AND status='finalizada' ORDER BY finalizada_em,id");
        $st->execute([$uid]);return $st->fetchAll();
    }

    public static function loads(int $uid): array
    {
        $st=db()->prepare("SELECT s.id AS sessao_id,s.finalizada_em,se.exercicio_id,MAX(sr.carga_kg) AS carga FROM sessoes s JOIN sessao_exercicios se ON se.sessao_id=s.id JOIN series_realizadas sr ON sr.sessao_exercicio_id=se.id WHERE s.usuario_id=? AND s.status='finalizada' AND sr.concluida=1 AND sr.carga_kg IS NOT NULL GROUP BY s.id,s.finalizada_em,se.exercicio_id ORDER BY s.finalizada_em,s.id,se.exercicio_id");
        $st->execute([$uid]);return $st->fetchAll();
    }
}
