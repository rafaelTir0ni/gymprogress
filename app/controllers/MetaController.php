<?php
declare(strict_types=1);

final class MetaController
{
    private function fail(string $message,int $code=422): never
    {
        http_response_code($code);exit(e($message));
    }
    private function id(): int
    {
        $value=$_GET['id']??$_POST['id']??null;
        $id=filter_var($value,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if(!$id)$this->fail('Identificador inválido.',400);
        return (int)$id;
    }
    private function postOnly(): void
    {
        if($_SERVER['REQUEST_METHOD']!=='POST')$this->fail('Método não permitido.',405);
        verifyCsrf();
    }
    private function validate(): array
    {
        $type=postValue('tipo');$title=preg_replace('/\s+/u',' ',postValue('titulo'))??'';
        $value=str_replace(',','.',postValue('valor_alvo'));
        $exercise=filter_var(postValue('exercicio_id'),FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        $start=postValue('inicio_em');$deadline=postValue('prazo_em');
        if(!in_array($type,['carga','frequencia_semanal','sessoes_acumuladas'],true))throw new DomainException('Tipo inválido.');
        if($title===''||mb_strlen($title)>150)throw new DomainException('Título deve ter de 1 a 150 caracteres.');
        if(!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D',$value)||(float)$value<=0||(float)$value>99999999)throw new DomainException('Informe um objetivo numérico positivo.');
        if($type!=='carga' && ((float)$value!==floor((float)$value)||(int)$value<1))throw new DomainException('Objetivo de frequência ou sessões deve ser inteiro.');
        if($type==='frequencia_semanal' && (int)$value>99)throw new DomainException('Frequência semanal deve ser de 1 a 99 treinos.');
        if($type==='carga'){
            if(!$exercise||!Exercicio::accessible((int)$exercise,currentUserId()))throw new DomainException('Escolha um exercício disponível da sua Biblioteca.');
        }else $exercise=null;
        foreach([$start,$deadline] as $date){
            if($date==='')continue;
            $d=DateTimeImmutable::createFromFormat('!Y-m-d',$date);
            if(!$d||$d->format('Y-m-d')!==$date)throw new DomainException('Data inválida.');
        }
        if($start===''||($deadline!==''&&$deadline<$start))throw new DomainException('O prazo deve ser posterior ao início.');
        return [$type,$title,number_format((float)$value,2,'.',''),$exercise,$start,$deadline?:null];
    }
    private function exercises(): array
    {
        return Exercicio::available(currentUserId());
    }
    public function index(): void
    {
        requireLogin();ConquistaService::sync(currentUserId());$metas=MetaService::listForUser(currentUserId());$records=EstatisticaService::records(currentUserId());
        $notice=takeFlash();require dirname(__DIR__).'/views/metas/index.php';
    }
    public function create(): void
    {
        requireLogin();$error='';$exercises=$this->exercises();
        $todayLocal=(new DateTimeImmutable('now',EstatisticaService::timezone(currentUserId())))->format('Y-m-d');
        if($_SERVER['REQUEST_METHOD']==='POST'){
            verifyCsrf();
            try{
                [$type,$title,$target,$exercise,$start,$deadline]=$this->validate();
                db()->prepare('INSERT INTO metas(usuario_id,exercicio_id,tipo,titulo,valor_alvo,inicio_em,prazo_em) VALUES(?,?,?,?,?,?,?)')
                    ->execute([currentUserId(),$exercise,$type,$title,$target,$start,$deadline]);
                ConquistaService::sync(currentUserId());flash('Meta criada.');go('metas');
            }catch(DomainException $e){$error=$e->getMessage();}
        }
        $editing=null;require dirname(__DIR__).'/views/metas/criar.php';
    }
    public function edit(): void
    {
        requireLogin();$id=$this->id();$editing=Meta::own(currentUserId(),$id);
        if(!$editing)$this->fail('Meta não encontrada.',404);
        $error='';$exercises=$this->exercises();$state=null;
        foreach(MetaService::listForUser(currentUserId()) as $row)if((int)$row['id']===$id)$state=$row['progresso']['estado'];
        if($state==='alcancada'||$state==='arquivada')$this->fail('Somente metas em acompanhamento podem ser editadas.');
        if($_SERVER['REQUEST_METHOD']==='POST'){
            verifyCsrf();
            try{
                [$type,$title,$target,$exercise,$start,$deadline]=$this->validate();
                if($state==='prazo_encerrado'){
                    $today=(new DateTimeImmutable('now',EstatisticaService::timezone(currentUserId())))->format('Y-m-d');
                    if(!$deadline||$deadline<=$today||($editing['prazo_em']!==null&&$deadline<=$editing['prazo_em']))throw new DomainException('Prorrogue o prazo para uma data futura.');
                    if($start!==$editing['inicio_em'])throw new DomainException('Não altere o início de uma meta com prazo encerrado.');
                }
                db()->beginTransaction();
                $current=Meta::own(currentUserId(),$id,true);
                if(!$current)throw new DomainException('Meta não encontrada.');
                db()->prepare('UPDATE metas SET exercicio_id=?,tipo=?,titulo=?,valor_alvo=?,inicio_em=?,prazo_em=? WHERE id=? AND usuario_id=?')
                    ->execute([$exercise,$type,$title,$target,$start,$deadline,$id,currentUserId()]);
                ConquistaService::sync(currentUserId());db()->commit();flash('Meta atualizada.');go('metas');
            }catch(DomainException $e){if(db()->inTransaction())db()->rollBack();$error=$e->getMessage();}
            catch(Throwable $e){if(db()->inTransaction())db()->rollBack();throw $e;}
        }
        require dirname(__DIR__).'/views/metas/editar.php';
    }
    public function action(): void
    {
        requireLogin();$this->postOnly();$id=$this->id();$action=postValue('acao');
        if(!in_array($action,['arquivar','reativar'],true))$this->fail('Ação inválida.');
        db()->beginTransaction();
        try{
            $goal=Meta::own(currentUserId(),$id,true);
            if(!$goal)throw new DomainException('Meta não encontrada.');
            db()->prepare('UPDATE metas SET status_base=? WHERE id=? AND usuario_id=?')
                ->execute([$action==='arquivar'?'arquivada':'ativa',$id,currentUserId()]);
            ConquistaService::sync(currentUserId());db()->commit();
        }catch(Throwable $e){if(db()->inTransaction())db()->rollBack();throw $e;}
        flash($action==='arquivar'?'Meta arquivada.':'Meta reativada.');go('metas');
    }
    public function achievements(): void
    {
        requireLogin();ConquistaService::sync(currentUserId());
        $achievements=UsuarioConquista::list(currentUserId());
        require dirname(__DIR__).'/views/metas/conquistas.php';
    }
    public function notice(): void
    {
        requireLogin();$this->postOnly();$id=filter_var($_POST['id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if(!$id)$this->fail('Aviso inválido.',400);
        $action=postValue('acao');if(!in_array($action,['ver','dispensar'],true))$this->fail('Ação inválida.');
        $column=$action==='ver'?'aviso_visualizado_em':'aviso_dispensado_em';
        db()->prepare("UPDATE usuario_conquistas SET $column=COALESCE($column,UTC_TIMESTAMP()) WHERE id=? AND usuario_id=? AND valida=1")
            ->execute([(int)$id,currentUserId()]);
        go($action==='ver'?'metas':'dashboard');
    }
}
