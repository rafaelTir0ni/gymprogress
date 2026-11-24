<?php $pageTitle='Fichas'; require __DIR__ . '/_top.php'; ?>
<div class="page-head"><div><h1>Minhas fichas</h1></div><a class="button primary" href="<?= e(routeUrl('fichas-criar')) ?>">Nova ficha</a></div>
<?php if ($notice !== ''): ?><p class="alert" role="status"><?= e($notice) ?></p><?php endif; ?>
<form class="filters panel" action="<?= e(routeUrl('fichas')) ?>" method="get">
<input type="hidden" name="route" value="fichas">
<label class="sr-only" for="busca">Buscar fichas</label><input type="search" id="busca" name="busca" value="<?= e($search) ?>" placeholder="Buscar pelo nome">
<label class="sr-only" for="filtro">Filtrar fichas</label><select id="filtro" name="filtro"><option value="todas" <?= $filter==='todas'?'selected':'' ?>>Todas</option><option value="ativas" <?= $filter==='ativas'?'selected':'' ?>>Ativas</option><option value="arquivadas" <?= $filter==='arquivadas'?'selected':'' ?>>Arquivadas</option></select>
<button type="submit" class="secondary">Filtrar</button></form>
<?php if (!$fichas): ?><div class="panel empty"><h2>Nenhuma ficha encontrada</h2><p class="muted">Crie sua primeira ficha ou ajuste os filtros.</p><a class="button primary" href="<?= e(routeUrl('fichas-criar')) ?>">Criar ficha</a></div><?php else: ?>
<div class="card-grid"><?php foreach ($fichas as $ficha): ?><a class="panel ficha-card" href="<?= e(routeUrl('fichas-detalhes').'&id='.(int)$ficha['id']) ?>"><div class="card-header"><h2><?= e($ficha['nome']) ?></h2><span class="pill <?= $ficha['status']==='ativa'?'active':'archived' ?>"><?= $ficha['status']==='ativa'?'Ativa':'Arquivada' ?></span></div><p class="muted"><?= e($ficha['grupo_muscular'] ?: 'Sem grupo muscular principal') ?></p><p class="small"><?= (int)$ficha['quantidade_exercicios'] ?> exercício(s)</p><span class="detail-link" aria-hidden="true">›</span></a><?php endforeach; ?></div>
<?php endif; ?>
<?php require __DIR__ . '/_bottom.php'; ?>
