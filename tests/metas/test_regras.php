<?php
declare(strict_types=1);
// Testes unitários básicos de lógica (sem acesso ao MySQL). Execute: php tests/metas/test_regras.php
require dirname(__DIR__,2).'/app/services/EstatisticaService.php';
require dirname(__DIR__,2).'/app/services/MetaService.php';
$tz=new DateTimeZone('America/Sao_Paulo');
$state=(new ReflectionClass(MetaService::class))->getMethod('state');
$goal=['tipo'=>'sessoes_acumuladas','valor_alvo'=>'2.00','inicio_em'=>'2026-01-01','prazo_em'=>null,'status_base'=>'ativa','exercicio_id'=>null];
$sessions=[['dia'=>'2026-01-10','semana'=>'2026-01-05','finalizada_em'=>'2026-01-10 12:00:00'],['dia'=>'2026-01-11','semana'=>'2026-01-05','finalizada_em'=>'2026-01-11 12:00:00']];
$a=$state->invoke(null,$goal,$sessions,[],$tz);
if($a['estado']!=='alcancada' || $a['atual']!==2.0)throw new RuntimeException('Meta acumulada');
$expired=$goal;$expired['prazo_em']='2026-01-09';
$b=$state->invoke(null,$expired,$sessions,[],$tz);
if($b['estado']!=='prazo_encerrado' || $b['atual']!==0.0)throw new RuntimeException('Prazo encerrado');
$load=$goal;$load['tipo']='carga';$load['valor_alvo']='50.00';$load['exercicio_id']=5;
$c=$state->invoke(null,$load,[],[['exercicio_id'=>5,'dia'=>'2026-01-11','carga'=>'55.00','finalizada_em'=>'2026-01-11 13:00:00']],$tz);
if($c['estado']!=='alcancada' || $c['atual']!==55.0)throw new RuntimeException('Meta de carga');
if(EstatisticaService::weekStart('2026-11-15')!=='2026-11-09')throw new RuntimeException('Semana');
echo "Testes unitários concluídos.\n";
