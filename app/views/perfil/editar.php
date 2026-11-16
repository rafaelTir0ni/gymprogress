<?php $pageTitle = 'Editar perfil'; require __DIR__ . '/_top.php'; ?>
<a class="perfil-back" href="<?= e(routeUrl('perfil')) ?>">← Voltar ao perfil</a><h1>Editar dados</h1><p class="perfil-muted">Atualize seu nome e e-mail de acesso.</p>
<?php if ($error !== ''): ?><p class="perfil-error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="perfil-card perfil-form" action="<?= e(routeUrl('perfil-editar')) ?>">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label>Nome<input name="nome" required maxlength="120" autocomplete="name" value="<?= e($values['nome']) ?>"></label>
<label>E-mail<input type="email" name="email" required maxlength="190" autocomplete="email" value="<?= e($values['email']) ?>"></label>
<button type="submit">Salvar alterações</button>
</form>
<?php require __DIR__ . '/_bottom.php'; ?>
