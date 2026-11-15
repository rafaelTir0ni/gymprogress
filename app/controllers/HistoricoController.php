<?php
declare(strict_types=1);

final class HistoricoController
{
    private function fail(int $code,string $msg): never { http_response_code($code);exit(e($msg)); }
    private function id(mixed $value): int
    {
        $id=filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if($id===false||$id===null)$this->fail(400,'Identificador inválido.');return (int)$id;
    }
    private function own(int $id): array
    {
        $session=Sessao::own($id,currentUserId());
        if(!$session||$session['status']!=='finalizada')$this->fail(404,'Treino finalizado não encontrado.');
        return $session;
    }
    private function dateParam(string $key): string
    {
        $value=$_GET[$key]??'';
        if(!is_string($value)||($value!==''&&!preg_match('/^\d{4}-\d{2}-\d{2}$/D',$value)))return '';
        return $value;
    }
    public function index(): void
    {
        requireLogin();$from=$this->dateParam('de');$to=$this->dateParam('ate');
        $fid=filter_var($_GET['ficha']??0,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);$fid=$fid?(int)$fid:0;
        $sessions=HistoricoService::list(currentUserId(),$from,$to,$fid);
        $fichas=HistoricoService::fichas(currentUserId());$notice=takeFlash();
        require dirname(__DIR__).'/views/historico/index.php';
    }
    public function details(): void
    {
        requireLogin();$sid=$this->id($_GET['id']??null);$session=$this->own($sid);
        $items=Sessao::items($sid);$series=Sessao::series($sid);$totals=Sessao::counts($sid);
        $changes=HistoricoAlteracao::listForSession($sid);$notice=takeFlash();
        require dirname(__DIR__).'/views/historico/detalhes.php';
    }
    public function edit(): void
    {
        requireLogin();$sid=$this->id($_GET['id']??null);$this->own($sid);
        $data=HistoricoService::snapshot($sid);
        $st=db()->prepare('SELECT id,nome,grupo_muscular FROM exercicios WHERE usuario_id IS NULL OR usuario_id=? ORDER BY grupo_muscular,nome,id');
        $st->execute([currentUserId()]);$exercises=$st->fetchAll();
        $notice=takeFlash();require dirname(__DIR__).'/views/historico/editar.php';
    }
    public function save(): void
    {
        requireLogin();if($_SERVER['REQUEST_METHOD']!=='POST')$this->fail(405,'Método inválido.');verifyCsrf();
        $sid=$this->id($_POST['id']??null);$this->own($sid);
        if(postValue('confirmar')!=='CORRIGIR'){$this->fail(422,'Confirme a edição digitando CORRIGIR.');}
        $payload=postValue('payload');
        if(strlen($payload)>150000)$this->fail(413,'Correção muito grande.');
        try {
            $parsed=json_decode($payload,true,64,JSON_THROW_ON_ERROR);
            if(!is_array($parsed))throw new DomainException('Dados de edição inválidos.');
            HistoricoService::correct($sid,currentUserId(),$parsed);
            flash('Correção salva com auditoria. Gráficos de carga consultam os dados atuais.');
            header('Location: '.routeUrl('historico-detalhes').'&id='.$sid,true,303);exit;
        }catch(JsonException|DomainException $e){flash($e->getMessage());
            header('Location: '.routeUrl('historico-editar').'&id='.$sid,true,303);exit;}
    }
}
