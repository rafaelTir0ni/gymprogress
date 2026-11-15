<?php $pageTitle='Editar ficha'; require __DIR__ . '/_top.php'; ?>
<a class="back" href="<?= e(routeUrl('fichas-detalhes') . '&id=' . (int)$ficha['id']) ?>">← Voltar à ficha</a>
<h1>Editar ficha</h1><p class="muted">As sessões finalizadas preservam seus registros históricos.</p>
<?php $formAction = routeUrl('fichas-editar') . '&id=' . (int)$ficha['id']; $cancelUrl = routeUrl('fichas-detalhes') . '&id=' . (int)$ficha['id']; $submitLabel = 'Salvar alterações'; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/_bottom.php'; ?>
