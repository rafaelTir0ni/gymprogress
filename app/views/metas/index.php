<?php $pageTitle='Metas';require __DIR__.'/_top.php'; ?>
<section class="meta-heading"><p class="meta-kicker">SUA EVOLUÇÃO</p><h1>Metas</h1><p>O progresso é calculado usando apenas treinos finalizados e séries concluídas.</p></section>
<?php if($notice): ?><p class="meta-message" role="status"><?= e($notice) ?></p><?php endif; ?>
<div class="meta-links"><a class="meta-primary" href="<?= e(routeUrl('metas-criar')) ?>">+ Criar meta</a><a href="<?= e(routeUrl('metas-conquistas')) ?>">Ver conquistas →</a></div>
<?php if(!$metas): ?><div class="meta-card"><h2>Nenhuma meta criada</h2><p>Defina uma meta de carga, frequência ou número de treinos. O prazo é opcional.</p></div><?php endif; ?>
<?php foreach($metas as $m): $g=$m['progresso'];$labels=['ativa'=>'Em andamento','alcancada'=>'Alcançada','prazo_encerrado'=>'Prazo encerrado','arquivada'=>'Arquivada']; ?>
<article class="meta-card">
<div class="meta-head"><h2><?= e($m['titulo']) ?></h2><span class="meta-status meta-<?= e($g['estado']) ?>"><?= e($labels[$g['estado']]??$g['estado']) ?></span></div>
<p class="meta-muted"><?= e(match($m['tipo']){'carga'=>'Carga máxima','frequencia_semanal'=>'Frequência semanal',default=>'Treinos acumulados'}) ?><?= $m['exercicio_nome']?' · '.e($m['exercicio_nome']):'' ?></p>
<div class="meta-values"><strong><?= e(number_format((float)$g['atual'],$m['tipo']==='carga'?2:0,',','.')) ?></strong> / <?= e(number_format((float)$g['alvo'],$m['tipo']==='carga'?2:0,',','.')) ?> <?= $m['tipo']==='carga'?'kg':($m['tipo']==='frequencia_semanal'?'treinos/semana':'treinos') ?></div>
<div class="meta-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= (int)$g['porcentagem'] ?>"><span style="width:<?= (int)$g['porcentagem'] ?>%"></span></div>
<p class="meta-muted">Início: <?= e(date('d/m/Y',strtotime($m['inicio_em']))) ?> · Prazo: <?= $m['prazo_em']?e(date('d/m/Y',strtotime($m['prazo_em']))):'Sem prazo' ?></p>
<?php if($g['atingida_em']): ?><p class="meta-muted">Alcançada em <?= e(date('d/m/Y',strtotime($g['atingida_em']))) ?> (UTC)</p><?php endif; ?>
<div class="meta-actions">
<?php if(!in_array($g['estado'],['alcancada','arquivada'],true)): ?><a href="<?= e(routeUrl('metas-editar').'&id='.(int)$m['id']) ?>"><?= $g['estado']==='prazo_encerrado'?'Prorrogar/editar':'Editar' ?></a><?php endif; ?>
<form method="post" action="<?= e(routeUrl('metas-acao')) ?>"><input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><input type="hidden" name="acao" value="<?= $m['status_base']==='arquivada'?'reativar':'arquivar' ?>"><button type="submit"><?= $m['status_base']==='arquivada'?'Reativar':'Arquivar' ?></button></form>
</div></article>
<?php endforeach; ?>
<section class="meta-card"><h2>Seus recordes de carga</h2><p class="meta-muted">Cargas máximas por exercício, considerando apenas séries concluídas de sessões finalizadas.</p>
<?php if(!$records): ?><p class="meta-muted">Os recordes aparecerão quando você finalizar treinos com carga registrada.</p><?php endif; ?>
<?php foreach($records as $r): ?><div class="meta-record"><strong><?= e($r['nome']) ?></strong><span>Máxima: <?= e(number_format($r['max'],2,',','.')) ?> kg<br>Esta semana: <?= isset($r['semana'])?e(number_format($r['semana'],2,',','.')).' kg':'—' ?></span></div><?php endforeach; ?>
</section>
<?php require __DIR__.'/_bottom.php'; ?>
