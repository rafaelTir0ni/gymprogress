<?php
declare(strict_types=1);

final class ConquistaService
{
    /** Reconcilia resultados a partir da fonte de verdade. É seguro executar repetidamente. */
    public static function sync(int $uid): void
    {
        $pdo=db();$ownTransaction=!$pdo->inTransaction();
        if($ownTransaction)$pdo->beginTransaction();
        try{
            $catalog=Conquista::ensureCatalog();$tz=EstatisticaService::timezone($uid);
            $sessions=EstatisticaService::sessions($uid);$loads=EstatisticaService::loads($uid);
            $out=[];$total=0;$weeklyCount=[];
            foreach($sessions as $s){
                $total++;$day=EstatisticaService::localDay($s['finalizada_em'],$tz);$week=EstatisticaService::weekStart($day);
                $weeklyCount[$week]=($weeklyCount[$week]??0)+1;
                foreach([1,5,10,25,50,100] as $threshold){
                    if($total===$threshold){$key='sessions:'.$threshold;$out[$key]=['marco_sessoes',null,null,null,$threshold,$s['finalizada_em']];}
                }
                foreach([2,3,4,5,6,7] as $threshold){
                    if($weeklyCount[$week]===$threshold){$key='frequency:'.$week.':'.$threshold;$out[$key]=['frequencia',null,$week,date('Y-m-d',strtotime($week.' +6 days')),$threshold,$s['finalizada_em']];}
                }
            }
            $pr=[];$weekly=[];
            foreach($loads as $r){
                $eid=(int)$r['exercicio_id'];$weight=(float)$r['carga'];
                $day=EstatisticaService::localDay($r['finalizada_em'],$tz);$week=EstatisticaService::weekStart($day);
                if(isset($pr[$eid])&&$weight>$pr[$eid]){
                    $key='personal:'.$eid;$out[$key]=['recorde_pessoal',$eid,null,null,$weight,$r['finalizada_em']];
                }
                $pr[$eid]=max($pr[$eid]??$weight,$weight);
                $wk=$week.':'.$eid;
                if(isset($weekly[$wk])&&$weight>$weekly[$wk]){
                    $key='weekly:'.$wk;$out[$key]=['recorde_semanal',$eid,$week,date('Y-m-d',strtotime($week.' +6 days')),$weight,$r['finalizada_em']];
                }
                $weekly[$wk]=max($weekly[$wk]??$weight,$weight);
            }
            $existing=$pdo->prepare('SELECT id,chave_ocorrencia FROM usuario_conquistas WHERE usuario_id=? FOR UPDATE');
            $existing->execute([$uid]);$was=$existing->fetchAll();
            $up=$pdo->prepare('INSERT INTO usuario_conquistas (usuario_id,conquista_id,exercicio_id,periodo_inicio,periodo_fim,chave_ocorrencia,valor_atingido,conquistada_em,valida) VALUES (?,?,?,?,?,?,?,?,1) ON DUPLICATE KEY UPDATE conquista_id=VALUES(conquista_id),exercicio_id=VALUES(exercicio_id),periodo_inicio=VALUES(periodo_inicio),periodo_fim=VALUES(periodo_fim),valor_atingido=VALUES(valor_atingido),conquistada_em=VALUES(conquistada_em),valida=1');
            foreach($out as $key=>[$type,$eid,$start,$end,$val,$when]){
                if(!isset($catalog[$type]))continue;
                $up->execute([$uid,$catalog[$type],$eid,$start,$end,$key,number_format((float)$val,2,'.',''),$when]);
            }
            // Não remover fisicamente: manter chave de ocorrência e os avisos dispensados.
            $invalidate=$pdo->prepare('UPDATE usuario_conquistas SET valida=0 WHERE id=? AND usuario_id=?');
            foreach($was as $r)if(!isset($out[$r['chave_ocorrencia']]))$invalidate->execute([$r['id'],$uid]);
            MetaService::refresh($uid);
            if($ownTransaction)$pdo->commit();
        }catch(Throwable $e){if($ownTransaction&&$pdo->inTransaction())$pdo->rollBack();throw $e;}
    }
}
