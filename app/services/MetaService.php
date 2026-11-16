<?php
declare(strict_types=1);

final class MetaService
{
    private static function metrics(int $uid): array
    {
        $tz=EstatisticaService::timezone($uid);
        $sessions=EstatisticaService::sessions($uid);$loads=EstatisticaService::loads($uid);
        foreach($sessions as &$s){$s['dia']=EstatisticaService::localDay($s['finalizada_em'],$tz);$s['semana']=EstatisticaService::weekStart($s['dia']);}unset($s);
        foreach($loads as &$r){$r['dia']=EstatisticaService::localDay($r['finalizada_em'],$tz);$r['semana']=EstatisticaService::weekStart($r['dia']);}unset($r);
        return [$sessions,$loads,$tz];
    }

    private static function state(array $m,array $sessions,array $loads,DateTimeZone $tz): array
    {
        $start=$m['inicio_em'];$limit=$m['prazo_em']?:null;$goal=(float)$m['valor_alvo'];$type=$m['tipo'];
        $today=(new DateTimeImmutable('now',$tz))->format('Y-m-d');
        $current=0.0;$reached=null;$achievedProgress=0.0;
        if($type==='carga'){
            foreach($loads as $r){
                if((int)$r['exercicio_id']!==(int)$m['exercicio_id']||$r['dia']<$start||($limit&&$r['dia']>$limit))continue;
                $current=max($current,(float)$r['carga']);
                if($reached===null&&$current>=$goal)$reached=$r['finalizada_em'];
            }
        }elseif($type==='sessoes_acumuladas'){
            foreach($sessions as $s){
                if($s['dia']<$start||($limit&&$s['dia']>$limit))continue;
                $current++;
                if($reached===null&&$current>=$goal)$reached=$s['finalizada_em'];
            }
        }else{
            $weeks=[];
            foreach($sessions as $s){
                if($s['dia']<$start||($limit&&$s['dia']>$limit))continue;
                $weeks[$s['semana']]=($weeks[$s['semana']]??0)+1;
                if($reached===null&&$weeks[$s['semana']]>=$goal)$reached=$s['finalizada_em'];
            }
            // Enquanto ativa: exibir semana corrente. Após fim de prazo: preservar o melhor progresso.
            $week=EstatisticaService::weekStart($today);
            $current=($reached!==null||($limit&&$today>$limit))?(float)max([0,...array_values($weeks)]):(float)($weeks[$week]??0);
        }
        $status=$m['status_base']==='arquivada'?'arquivada':($reached!==null?'alcancada':($limit&&$today>$limit?'prazo_encerrado':'ativa'));
        return ['atual'=>$current,'alvo'=>$goal,'atingida_em'=>$reached,'estado'=>$status,'porcentagem'=>min(100,(int)round(($current/$goal)*100))];
    }

    public static function listForUser(int $uid): array
    {
        [$sessions,$loads,$tz]=self::metrics($uid);$rows=Meta::all($uid);
        foreach($rows as &$m)$m['progresso']=self::state($m,$sessions,$loads,$tz);
        unset($m);return $rows;
    }

    public static function refresh(int $uid): void
    {
        // Apenas status persistido; o progresso sempre é derivado das séries válidas.
        $rows=self::listForUser($uid);
        $st=db()->prepare('UPDATE metas SET atingida_em=? WHERE id=? AND usuario_id=? AND NOT (atingida_em <=> ?)');
        foreach($rows as $r){$value=$r['progresso']['atingida_em'];$st->execute([$value,$r['id'],$uid,$value]);}
    }
}
