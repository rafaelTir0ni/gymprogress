<?php
declare(strict_types=1);

final class SessaoController
{
    private function fail(int $code,string $message): never
    {
        http_response_code($code);exit(e($message));
    }
    private function id(mixed $value): int
    {
        $id=filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if ($id===false || $id===null) $this->fail(400,'Identificador inválido.');
        return (int)$id;
    }
    private function post(): void
    {
        requireLogin(); if ($_SERVER['REQUEST_METHOD']!=='POST') $this->fail(405,'Método inválido.'); verifyCsrf();
    }
    private function redirect(string $route='treinar',array $params=[]): never
    {
        $url=routeUrl($route);
        foreach($params as $k=>$v) $url.='&'.rawurlencode((string)$k).'='.rawurlencode((string)$v);
        header('Location: '.$url,true,303);exit;
    }
    private function tx(callable $cb): mixed
    {
        db()->beginTransaction();
        try { $result=$cb(); db()->commit();return $result; }
        catch(Throwable $e){ if(db()->inTransaction())db()->rollBack();throw $e; }
    }
    private function withActive(int $sid,callable $cb): mixed
    {
        return $this->tx(function() use($sid,$cb){
            $session=Sessao::own($sid,currentUserId(),true);
            if (!$session || $session['status']!=='em_andamento') throw new DomainException('Sessão não está mais em andamento.');
            return $cb($session);
        });
    }
    private function returnSession(int $sid,int $item=0): never
    {
        $this->redirect('treinar-andamento',['id'=>$sid]+($item?['ex'=>$item]:[]));
    }
    private function friendly(callable $cb,int $sid): never
    {
        try{$cb();}catch(DomainException $e){flash($e->getMessage());}
        $this->returnSession($sid);
    }

    public function index(): void
    {
        requireLogin();$active=Sessao::active(currentUserId());$fichas=Sessao::eligible(currentUserId());$notice=takeFlash();
        require dirname(__DIR__).'/views/sessoes/iniciar.php';
    }
    public function start(): void
    {
        $this->post();$fid=$this->id($_POST['ficha_id']??null);
        try {
            $sid=$this->tx(function()use($fid){
                // lock the user row to serialize start requests for this account.
                $l=db()->prepare('SELECT id FROM usuarios WHERE id=? FOR UPDATE');$l->execute([currentUserId()]);
                if(Sessao::active(currentUserId()))throw new DomainException('Você já tem uma sessão ativa. Retome-a.');
                $ficha=Ficha::find($fid,currentUserId(),true);
                if(!$ficha || $ficha['status']!=='ativa')throw new DomainException('Escolha uma ficha ativa da sua conta.');
                $st=db()->prepare('SELECT fe.*,e.nome,e.grupo_muscular,e.usuario_id AS dono_exercicio
                    FROM ficha_exercicios fe JOIN exercicios e ON e.id=fe.exercicio_id
                    WHERE fe.ficha_id=? ORDER BY fe.ordem,fe.id FOR UPDATE');$st->execute([$fid]);$rows=$st->fetchAll();
                if(!$rows)throw new DomainException('Adicione pelo menos um exercício à ficha antes de iniciar.');
                foreach($rows as $r)if($r['dono_exercicio']!==null && (int)$r['dono_exercicio']!==currentUserId()) throw new DomainException('Ficha contém exercício de outra conta.');
                db()->prepare('INSERT INTO sessoes(usuario_id,ficha_id,nome_ficha_snapshot,iniciada_em) VALUES(?,?,?,UTC_TIMESTAMP())')->execute([currentUserId(),$fid,$ficha['nome']]);
                $sid=(int)db()->lastInsertId();
                $add=db()->prepare('INSERT INTO sessao_exercicios(sessao_id,exercicio_id,nome_exercicio_snapshot,grupo_muscular_snapshot,ordem,series_planejadas_snapshot,reps_min_snapshot,reps_max_snapshot) VALUES(?,?,?,?,?,?,?,?)');
                $set=db()->prepare("INSERT INTO series_realizadas(sessao_exercicio_id,numero_serie,origem) VALUES(?,?,'planejada')");
                foreach($rows as $r){
                    $add->execute([$sid,$r['exercicio_id'],$r['nome'],$r['grupo_muscular'],$r['ordem'],$r['series_planejadas'],$r['reps_min'],$r['reps_max']]);
                    $itemId=(int)db()->lastInsertId();
                    for($n=1;$n<=(int)$r['series_planejadas'];$n++)$set->execute([$itemId,$n]);
                }
                return $sid;
            });
            flash('Treino iniciado! Os registros são salvos conforme você avança.');$this->returnSession($sid);
        }catch(DomainException $e){flash($e->getMessage());$this->redirect();}
        catch(PDOException $e){if($e->getCode()==='23000'){flash('Você já tem um treino ativo.');$this->redirect();}throw $e;}
    }
    public function show(): void
    {
        requireLogin();$sid=$this->id($_GET['id']??null);$session=Sessao::own($sid,currentUserId());
        if(!$session)$this->fail(404,'Sessão não encontrada.');
        if($session['status']==='finalizada'){$this->redirect('treinar-resumo',['id'=>$sid]);}
        $items=Sessao::items($sid);$series=Sessao::series($sid);$selected=$this->idOrFirst($_GET['ex']??null,$items);
        $notice=takeFlash();require dirname(__DIR__).'/views/sessoes/andamento.php';
    }
    private function idOrFirst(mixed $raw,array $items): int
    {
        $id=filter_var($raw,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        foreach($items as $it)if((int)$it['id']===$id)return (int)$id;
        return $items?(int)$items[0]['id']:0;
    }
    public function save(): void
    {
        $this->post();$sid=$this->id($_POST['id']??null);$itemId=$this->id($_POST['item_id']??null);$setId=$this->id($_POST['serie_id']??null);
        try{$this->withActive($sid,function()use($sid,$itemId,$setId){
            $item=Sessao::item($itemId,$sid);if(!$item)throw new DomainException('Exercício não encontrado.');
            if($item['status']==='concluido_antecipadamente')throw new DomainException('Reabra o exercício antes de alterar suas séries.');
            $st=db()->prepare('SELECT * FROM series_realizadas WHERE id=? AND sessao_exercicio_id=? FOR UPDATE');$st->execute([$setId,$itemId]);$serie=$st->fetch();
            if(!$serie)throw new DomainException('Série não encontrada.');
            $action=postValue('acao');$done=$action==='concluir'||($action==='salvar' && (int)$serie['concluida']===1);$undo=$action==='desmarcar';
            if(!in_array(postValue('acao'),['salvar','concluir','desmarcar'],true))throw new DomainException('Ação inválida.');
            if($undo){db()->prepare('UPDATE series_realizadas SET concluida=0,concluida_em=NULL WHERE id=?')->execute([$setId]);}
            else{
                $rawReps=postValue('repeticoes');$rawWeight=postValue('carga_kg');
                if($rawReps!=='' && (!ctype_digit($rawReps) || (int)$rawReps<1 || (int)$rawReps>65535))throw new DomainException('Repetições devem estar entre 1 e 65535.');
                if($done && $rawReps==='')throw new DomainException('Informe as repetições antes de concluir a série.');
                if($rawWeight!=='' && (!preg_match('/^\d{1,5}(?:[.,]\d{1,2})?$/',$rawWeight) || (float)str_replace(',','.',$rawWeight)>99999.99)) throw new DomainException('Informe uma carga válida ou deixe em branco.');
                $weight=$rawWeight===''?null:str_replace(',','.',$rawWeight);
                $reps=$rawReps===''?null:(int)$rawReps;
                db()->prepare('UPDATE series_realizadas SET repeticoes=?,carga_kg=?,concluida=?,concluida_em=CASE WHEN ?=1 THEN COALESCE(concluida_em,UTC_TIMESTAMP()) ELSE NULL END WHERE id=?')
                    ->execute([$reps,$weight,$done?1:0,$done?1:0,$setId]);
            }
            Sessao::recalculateItem($itemId,$sid);
        });flash('Série atualizada.');}
        catch(DomainException $e){flash($e->getMessage());}
        $this->returnSession($sid,$itemId);
    }
    public function addSet(): void
    {
        $this->post();$sid=$this->id($_POST['id']??null);$itemId=$this->id($_POST['item_id']??null);
        try{$this->withActive($sid,function()use($sid,$itemId){
            $item=Sessao::item($itemId,$sid);if(!$item)throw new DomainException('Exercício não encontrado.');
            if($item['status']==='concluido_antecipadamente')throw new DomainException('Reabra o exercício antes de adicionar séries.');
            $st=db()->prepare('SELECT COALESCE(MAX(numero_serie),0) FROM series_realizadas WHERE sessao_exercicio_id=?');$st->execute([$itemId]);$num=(int)$st->fetchColumn()+1;
            if($num>65535)throw new DomainException('Limite de séries atingido.');
            db()->prepare("INSERT INTO series_realizadas(sessao_exercicio_id,numero_serie,origem) VALUES(?,?,'extra')")->execute([$itemId,$num]);
            Sessao::recalculateItem($itemId,$sid);
        });flash('Série extra adicionada.');}catch(DomainException $e){flash($e->getMessage());}
        $this->returnSession($sid,$itemId);
    }
    public function exercise(): void
    {
        $this->post();$sid=$this->id($_POST['id']??null);$itemId=$this->id($_POST['item_id']??null);$action=postValue('acao');
        try{$this->withActive($sid,function()use($sid,$itemId,$action){
            $item=Sessao::item($itemId,$sid);if(!$item)throw new DomainException('Exercício não encontrado.');
            if($action==='reabrir'){
                if(!in_array($item['status'],['concluido','concluido_antecipadamente'],true))throw new DomainException('Exercício ainda não está concluído.');
                db()->prepare("UPDATE sessao_exercicios SET status='parcialmente_realizado',concluido_em=NULL WHERE id=? AND sessao_id=?")->execute([$itemId,$sid]);
            }
            elseif($action==='antecipar'){
                $st=db()->prepare('SELECT COUNT(*) FROM series_realizadas WHERE sessao_exercicio_id=? AND concluida=1');$st->execute([$itemId]);
                if(!(int)$st->fetchColumn())throw new DomainException('Conclua ao menos uma série para encerrar antecipadamente este exercício.');
                Sessao::recalculateItem($itemId,$sid,true);
            }else throw new DomainException('Ação inválida.');
        });flash($action==='reabrir'?'Exercício reaberto.':'Exercício concluído antecipadamente.');}catch(DomainException $e){flash($e->getMessage());}
        $this->returnSession($sid,$itemId);
    }
    public function preview(): void
    {
        requireLogin();$sid=$this->id($_GET['id']??null);$session=Sessao::own($sid,currentUserId());
        if(!$session)$this->fail(404,'Sessão não encontrada.');
        $items=Sessao::items($sid);$series=Sessao::series($sid);$totals=Sessao::counts($sid);$notice=takeFlash();
        require dirname(__DIR__).'/views/sessoes/finalizar.php';
    }
    public function finish(): void
    {
        $this->post();$sid=$this->id($_POST['id']??null);
        try{$this->withActive($sid,function($session)use($sid){
            $counts=Sessao::counts($sid);
            if(!(int)$counts['feitas'])throw new DomainException('Conclua ao menos uma série. Você pode continuar ou descartar a sessão.');
            db()->prepare("UPDATE sessoes SET status='finalizada', finalizada_em=UTC_TIMESTAMP(), duracao_segundos=TIMESTAMPDIFF(SECOND,iniciada_em,UTC_TIMESTAMP()) WHERE id=? AND usuario_id=? AND status='em_andamento'")
                ->execute([$sid,currentUserId()]);
        });flash('Sessão finalizada! Os registros já estão disponíveis para o Histórico.');}
        catch(DomainException $e){flash($e->getMessage());$this->redirect('treinar-resumo',['id'=>$sid]);}
        $this->redirect('treinar-resumo',['id'=>$sid]);
    }
    public function discard(): void
    {
        $this->post();$sid=$this->id($_POST['id']??null);
        if(postValue('confirmar')!=='DESCARTAR'){$this->fail(422,'Digite DESCARTAR para confirmar.');}
        try{$this->withActive($sid,function()use($sid){
            // Dependents (sessao_exercicios/series_realizadas) are removed by FK CASCADE.
            $st=db()->prepare("DELETE FROM sessoes WHERE id=? AND usuario_id=? AND status='em_andamento'");
            $st->execute([$sid,currentUserId()]);
            if($st->rowCount()!==1)throw new DomainException('Não foi possível descartar a sessão.');
        });flash('Sessão em andamento descartada definitivamente.');}
        catch(DomainException $e){flash($e->getMessage());}
        $this->redirect();
    }
    public function graph(): void
    {
        requireLogin();$exerciseId=$this->id($_GET['exercicio_id']??null);
        if(!Exercicio::visible($exerciseId,currentUserId()))$this->fail(404,'Exercício não encontrado.');
        $points=Sessao::graph(currentUserId(),$exerciseId);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['labels'=>array_column($points,'semana'),'values'=>array_map('floatval',array_column($points,'carga'))],JSON_THROW_ON_ERROR);
    }
}
