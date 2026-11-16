<?php $pageTitle='Editar meta';require __DIR__.'/_top.php'; ?>
<a class="meta-back" href="<?= e(routeUrl('metas')) ?>">← Minhas metas</a><h1>Editar meta</h1>
<?php if($state==='prazo_encerrado'): ?><p class="meta-message">Prazo encerrado: escolha uma data futura para retomar o acompanhamento.</p><?php endif; ?>
<?php require __DIR__.'/_form.php'; ?>
<?php require __DIR__.'/_bottom.php'; ?>
