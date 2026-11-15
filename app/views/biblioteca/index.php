<?php $pageTitle='Biblioteca'; require __DIR__.'/_top.php'; ?>
<div class="page-head"><div><h1>Biblioteca</h1><p class="muted">Encontre e organize os exercícios.</p></div><a class="button primary" href="<?= e(routeUrl('biblioteca-criar')) ?>">+ Exercício</a></div>
<?php if ($notice !== ''): ?><p role="status" class="alert"><?= e($notice) ?></p><?php endif; ?>
<form class="panel library-filters" method="get" action="<?= e(routeUrl('biblioteca')) ?>">
<input type="hidden" name="route" value="biblioteca">
<label>Pesquisar pelo nome<input type="search" name="busca" maxlength="120" placeholder="Ex.: Supino" value="<?= e($search) ?>"></label>
<label>Grupo muscular<select name="grupo"><option value="">Todos</option><?php foreach (Exercicio::GROUPS as $g): ?><option value="<?= e($g) ?>" <?= $g===$group?'selected':'' ?>><?= e(['Bracos'=>'Braços','Abdomen'=>'Abdômen'][$g] ?? $g) ?></option><?php endforeach; ?></select></label>
<label>Tipo<select name="tipo"><option value="todos" <?= $scope==='todos'?'selected':'' ?>>Todos</option><option value="gerais" <?= $scope==='gerais'?'selected':'' ?>>Gerais</option><option value="meus" <?= $scope==='meus'?'selected':'' ?>>Meus exercícios</option></select></label>
<label class="check-filter"><input type="checkbox" name="arquivados" value="1" <?= $archived?'checked':'' ?>> Mostrar meus arquivados</label>
<button class="secondary" type="submit">Aplicar filtros</button></form>
<?php if (!$exercicios): ?><div class="panel empty"><h2>Nenhum exercício encontrado</h2><p class="muted">Experimente limpar os filtros ou adicionar seu primeiro exercício.</p><a class="button primary" href="<?= e(routeUrl('biblioteca-criar')) ?>">Criar exercício</a></div>
<?php else: ?><p class="muted small"><?= count($exercicios) ?> resultado(s)<?= count($exercicios)===300?' (limite de exibição: 300)':'' ?></p>
<div class="library-grid"><?php foreach ($exercicios as $ex): ?><article class="panel library-card"><div class="card-header"><span class="pill <?= $ex['status']==='ativo'?'active':'archived' ?>"><?= $ex['status']==='ativo'?'Ativo':'Arquivado' ?></span><span class="muted small"><?= $ex['usuario_id']===null?'Geral':'Personalizado' ?></span></div><h2><?= e($ex['nome']) ?></h2><p class="muted small"><?= e(['Bracos'=>'Braços','Abdomen'=>'Abdômen'][$ex['grupo_muscular']] ?? $ex['grupo_muscular']) ?></p><p class="muted small snippet"><?= e($ex['descricao'] ?: 'Sem descrição cadastrada.') ?></p><a class="button secondary" href="<?= e(routeUrl('biblioteca-detalhes').'&id='.(int)$ex['id']) ?>">Detalhes <?= $ex['status']==='ativo'?'/ Adicionar +':'' ?></a></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__.'/_bottom.php'; ?>
