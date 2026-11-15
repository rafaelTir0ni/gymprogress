<?php $pageTitle='Treinar';require __DIR__.'/_top.php'; ?>
<h1>Treinar</h1><p class="muted">Escolha uma ficha ativa para registrar seu treino.</p>
<?php if($notice): ?><p class="alert" role="status"><?= e($notice) ?></p><?php endif; ?>
<?php if($active): ?><div class="panel workout-active"><span class="pill active">Em andamento</span><h2><?= e($active['nome_ficha_snapshot']) ?></h2><p class="muted small">Iniciado em <?= e($active['iniciada_em']) ?> (UTC)</p><a class="button primary" href="<?= e(routeUrl('treinar-andamento').'&id='.$active['id']) ?>">Continuar treino →</a></div><?php endif; ?>
<section class="section"><h2>Suas fichas</h2>
<?php if(!$fichas): ?><div class="panel empty"><p>Nenhuma ficha ativa com exercícios.</p><a class="button secondary" href="<?= e(routeUrl('fichas')) ?>">Organizar fichas</a></div><?php endif; ?>
<?php foreach($fichas as $f): ?><article class="panel"><div class="card-header"><div><h3><?= e($f['nome']) ?></h3><p class="muted small"><?= e($f['grupo_muscular'] ?: 'Treino personalizado') ?> · <?= (int)$f['total'] ?> exercícios</p></div></div>
<?php if(!$active): ?><form method="post" action="<?= e(routeUrl('treinar-iniciar')) ?>" onsubmit="return confirm('Iniciar esta ficha?');"><input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><input type="hidden" name="ficha_id" value="<?= (int)$f['id'] ?>"><button class="primary" type="submit">Iniciar treino</button></form><?php else: ?><p class="muted small">Finalize ou descarte a sessão atual para iniciar outra.</p><?php endif; ?></article><?php endforeach; ?></section>
<?php require __DIR__.'/_bottom.php'; ?>
