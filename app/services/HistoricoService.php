<?php
declare(strict_types=1);

final class HistoricoService
{
    public static function list(int $userId,string $from,string $to,int $fichaId): array
    {
        $sql="SELECT s.id,s.nome_ficha_snapshot,s.iniciada_em,s.finalizada_em,s.duracao_segundos,f.nome nome_atual,
              (SELECT COUNT(DISTINCT se.id) FROM sessao_exercicios se JOIN series_realizadas sr ON sr.sessao_exercicio_id=se.id WHERE se.sessao_id=s.id AND sr.concluida=1) exercicios,
              (SELECT COUNT(*) FROM series_realizadas sr JOIN sessao_exercicios se ON se.id=sr.sessao_exercicio_id WHERE se.sessao_id=s.id AND sr.concluida=1) series
              FROM sessoes s JOIN fichas f ON f.id=s.ficha_id WHERE s.usuario_id=? AND s.status='finalizada'";
        $params=[$userId];
        if($from!==''){$sql.=' AND s.iniciada_em >= ?';$params[]=$from.' 00:00:00';}
        if($to!==''){$sql.=' AND s.iniciada_em < DATE_ADD(?,INTERVAL 1 DAY)';$params[]=$to;}
        if($fichaId>0){$sql.=' AND s.ficha_id=?';$params[]=$fichaId;}
        $sql.=' ORDER BY s.iniciada_em DESC,s.id DESC LIMIT 200';
        $st=db()->prepare($sql);$st->execute($params);return $st->fetchAll();
    }

    public static function fichas(int $userId): array
    {
        $st=db()->prepare("SELECT DISTINCT f.id,f.nome FROM fichas f JOIN sessoes s ON s.ficha_id=f.id WHERE s.usuario_id=? AND s.status='finalizada' ORDER BY f.nome");
        $st->execute([$userId]);return $st->fetchAll();
    }

    public static function snapshot(int $sessionId): array
    {
        $st=db()->prepare('SELECT id,nome_ficha_snapshot,iniciada_em,finalizada_em,duracao_segundos,observacoes FROM sessoes WHERE id=?');
        $st->execute([$sessionId]);$session=$st->fetch();
        $items=Sessao::items($sessionId);$sets=Sessao::series($sessionId);
        foreach($items as &$item){$item['sets']=$sets[(int)$item['id']]??[];}unset($item);
        return ['session'=>$session,'items'=>$items];
    }

    private static function dateUtc(string $raw): string
    {
        if(!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/D',$raw))throw new DomainException('Informe data e hora válidas.');
        $d=DateTimeImmutable::createFromFormat('!Y-m-d\TH:i',$raw,new DateTimeZone('UTC'));
        if(!$d||$d->format('Y-m-d\TH:i')!==$raw)throw new DomainException('Data ou horário inválido.');
        return $d->format('Y-m-d H:i:s');
    }

    private static function number(mixed $value,int $min,int $max,string $label): int
    {
        if(!is_int($value)&&!(is_string($value)&&ctype_digit($value)))throw new DomainException("$label inválido.");
        $n=(int)$value;if($n<$min||$n>$max)throw new DomainException("$label fora do intervalo permitido.");return $n;
    }

    private static function weight(mixed $value): ?string
    {
        if($value===null||$value==='')return null;
        if(!is_string($value)&&!is_int($value)&&!is_float($value))throw new DomainException('Carga inválida.');
        $v=str_replace(',','.',(string)$value);
        if(!preg_match('/^\d{1,5}(?:\.\d{1,2})?$/D',$v)|| (float)$v>99999.99)throw new DomainException('Carga inválida.');
        return number_format((float)$v,2,'.','');
    }

    public static function correct(int $sessionId,int $userId,array $input): void
    {
        db()->beginTransaction();
        try {
            $session=Sessao::own($sessionId,$userId,true);
            if(!$session||$session['status']!=='finalizada')throw new DomainException('Somente sessões finalizadas da sua conta podem ser corrigidas.');
            $before=self::snapshot($sessionId);
            $name=trim((string)($input['name']??''));
            if($name===''||(function_exists('mb_strlen')?mb_strlen($name):strlen($name))>120)throw new DomainException('Nome da sessão deve ter até 120 caracteres.');
            $notes=trim((string)($input['notes']??''));
            if((function_exists('mb_strlen')?mb_strlen($notes):strlen($notes))>5000)throw new DomainException('Observações devem ter até 5000 caracteres.');
            $start=self::dateUtc((string)($input['start']??''));$end=self::dateUtc((string)($input['end']??''));
            $seconds=strtotime($end.' UTC')-strtotime($start.' UTC');
            if($seconds<0||$seconds>315360000)throw new DomainException('O término deve ser posterior ao início.');
            $rawItems=$input['items']??null;
            if(!is_array($rawItems)||count($rawItems)<1||count($rawItems)>100||array_keys($rawItems)!==range(0,count($rawItems)-1))throw new DomainException('Informe de 1 a 100 exercícios.');
            $old=[];foreach($before['items'] as $r)$old[(int)$r['id']]=$r;
            $validated=[];$used=[];$doneTotal=0;
            $availableStmt=db()->prepare("SELECT id,nome,grupo_muscular,usuario_id FROM exercicios WHERE id=? AND (usuario_id IS NULL OR usuario_id=?)");
            foreach($rawItems as $pos=>$row){
                if(!is_array($row))throw new DomainException('Exercício inválido.');
                $itemId=self::number($row['id']??0,0,PHP_INT_MAX,'ID do exercício da sessão');
                if($itemId>0 && !isset($old[$itemId]))throw new DomainException('Um exercício informado não pertence à sessão.');
                if($itemId>0 && isset($used['i'.$itemId]))throw new DomainException('Exercício repetido na correção.');
                $used['i'.$itemId]=true;
                $exerciseId=self::number($row['exercise_id']??0,1,PHP_INT_MAX,'Exercício');
                if($itemId>0 && $exerciseId===(int)$old[$itemId]['exercicio_id']){
                    $label=$old[$itemId]['nome_exercicio_snapshot'];$group=$old[$itemId]['grupo_muscular_snapshot'];
                }else{
                    $availableStmt->execute([$exerciseId,$userId]);$exercise=$availableStmt->fetch();
                    if(!$exercise)throw new DomainException('Exercício inexistente ou de outra conta.');
                    $label=$exercise['nome'];$group=$exercise['grupo_muscular'];
                }
                $planned=self::number($row['planned']??0,1,65535,'Séries planejadas');
                $min=self::number($row['min']??0,1,65535,'Repetições mínimas');
                $max=self::number($row['max']??0,$min,65535,'Repetições máximas');
                $early=($row['early']??false)===true;
                $sets=$row['sets']??null;
                if(!is_array($sets)||count($sets)<1||count($sets)>200||array_keys($sets)!==range(0,count($sets)-1))throw new DomainException('Cada exercício precisa ter de 1 a 200 séries.');
                $setRows=[];$done=0;
                foreach($sets as $set){
                    if(!is_array($set))throw new DomainException('Série inválida.');
                    $complete=($set['done']??false)===true;
                    $reps=($set['reps']??'')===''?null:self::number($set['reps'],1,65535,'Repetições');
                    if($complete&&$reps===null)throw new DomainException('Série concluída precisa de repetições.');
                    $weight=self::weight($set['weight']??null);
                    if($complete){$done++;$doneTotal++;}
                    $setRows[]=['done'=>$complete,'reps'=>$reps,'weight'=>$weight,'origin'=>($set['origin']??'planejada')==='extra'?'extra':'planejada'];
                }
                $status=$done>=$planned?'concluido':($early&&$done>0?'concluido_antecipadamente':($done>0?'parcialmente_realizado':'pendente'));
                $validated[]=['id'=>$itemId,'exercise_id'=>$exerciseId,'name'=>$label,'group'=>$group,'planned'=>$planned,'min'=>$min,'max'=>$max,'status'=>$status,'sets'=>$setRows,'order'=>$pos+1];
            }
            if($doneTotal<1)throw new DomainException('Uma sessão finalizada precisa manter pelo menos uma série concluída.');
            db()->prepare('UPDATE sessoes SET nome_ficha_snapshot=?,iniciada_em=?,finalizada_em=?,duracao_segundos=?,observacoes=? WHERE id=? AND usuario_id=? AND status=\'finalizada\'')
                ->execute([$name,$start,$end,$seconds,$notes===''?null:$notes,$sessionId,$userId]);
            $keep=array_filter(array_column($validated,'id'),fn($n)=>$n>0);
            foreach(array_keys($old) as $id){if(!in_array($id,$keep,true)){
                db()->prepare('DELETE FROM sessao_exercicios WHERE id=? AND sessao_id=?')->execute([$id,$sessionId]);
            }}
            $insertItem=db()->prepare('INSERT INTO sessao_exercicios(sessao_id,exercicio_id,nome_exercicio_snapshot,grupo_muscular_snapshot,ordem,series_planejadas_snapshot,reps_min_snapshot,reps_max_snapshot,status,concluido_em) VALUES(?,?,?,?,?,?,?,?,?,IF(? IN (\'concluido\',\'concluido_antecipadamente\'),UTC_TIMESTAMP(),NULL))');
            $updateItem=db()->prepare('UPDATE sessao_exercicios SET exercicio_id=?,nome_exercicio_snapshot=?,grupo_muscular_snapshot=?,ordem=?,series_planejadas_snapshot=?,reps_min_snapshot=?,reps_max_snapshot=?,status=?,concluido_em=IF(? IN (\'concluido\',\'concluido_antecipadamente\'),COALESCE(concluido_em,UTC_TIMESTAMP()),NULL) WHERE id=? AND sessao_id=?');
            $insertSet=db()->prepare('INSERT INTO series_realizadas(sessao_exercicio_id,numero_serie,origem,repeticoes,carga_kg,concluida,concluida_em) VALUES(?,?,?,?,?,?,IF(?=1,UTC_TIMESTAMP(),NULL))');
            foreach($validated as $item){
                $id=$item['id'];
                if($id>0){$updateItem->execute([$item['exercise_id'],$item['name'],$item['group'],$item['order'],$item['planned'],$item['min'],$item['max'],$item['status'],$item['status'],$id,$sessionId]);
                    db()->prepare('DELETE FROM series_realizadas WHERE sessao_exercicio_id=?')->execute([$id]);
                }else{$insertItem->execute([$sessionId,$item['exercise_id'],$item['name'],$item['group'],$item['order'],$item['planned'],$item['min'],$item['max'],$item['status'],$item['status']]);$id=(int)db()->lastInsertId();}
                foreach($item['sets'] as $idx=>$set)$insertSet->execute([$id,$idx+1,$set['origin'],$set['reps'],$set['weight'],$set['done']?1:0,$set['done']?1:0]);
            }
            $after=self::snapshot($sessionId);
            HistoricoAlteracao::record($sessionId,$userId,$before,$after);
            ConquistaService::sync($userId);
            db()->commit();
        }catch(Throwable $error){if(db()->inTransaction())db()->rollBack();throw $error;}
    }
}
