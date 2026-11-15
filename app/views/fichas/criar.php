<?php $pageTitle='Nova ficha'; require __DIR__ . '/_top.php'; ?>
<a class="back" href="<?= e(routeUrl('fichas')) ?>">← Minhas fichas</a>
<h1>Criar ficha</h1><p class="muted">Você pode criar uma ficha vazia e adicionar exercícios depois.</p>
<?php $formAction = routeUrl('fichas-criar'); $cancelUrl = routeUrl('fichas'); $submitLabel = 'Criar ficha'; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/_bottom.php'; ?>
