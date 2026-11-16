<?php $pageTitle='Conquistas';require __DIR__.'/_top.php'; ?>
<a class="meta-back" href="<?= e(routeUrl('metas')) ?>">← Metas</a><h1>Suas conquistas</h1><p class="meta-muted">Somente registros válidos de treinos finalizados. Correções no Histórico recalculam estes resultados.</p>
<?php if(!$achievements): ?><div class="meta-card"><h2>Ainda não há conquistas</h2><p>Continue registrando seus treinos para desbloquear marcos e recordes.</p></div><?php endif; ?>
<?php foreach($achievements as $a): ?><article class="meta-card"><h2><?= e($a['nome']) ?></h2><p><?= e($a['descricao']) ?> <?= $a['exercicio_nome']?'· '.e($a['exercicio_nome']):'' ?></p><p class="meta-muted">Valor: <?= e(number_format((float)$a['valor_atingido'],2,',','.')) ?> <?= in_array($a['tipo'],['recorde_pessoal','recorde_semanal'],true)?'kg':'' ?> · <?= e(date('d/m/Y',strtotime($a['conquistada_em']))) ?> (UTC)</p></article><?php endforeach; ?>
<?php require __DIR__.'/_bottom.php'; ?>
