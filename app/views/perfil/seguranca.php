<?php $pageTitle = 'Segurança'; require __DIR__ . '/_top.php'; ?>
<a class="perfil-back" href="<?= e(routeUrl('perfil')) ?>">← Voltar ao perfil</a><h1>Alterar senha</h1><p class="perfil-muted">Para sua segurança, confirme a senha atual. Você precisará entrar novamente após a alteração.</p>
<?php if ($error !== ''): ?><p class="perfil-error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="perfil-card perfil-form" action="<?= e(routeUrl('perfil-seguranca')) ?>">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label>Senha atual<input type="password" name="senha_atual" required autocomplete="current-password"></label>
<label>Nova senha<input type="password" name="nova_senha" required minlength="8" autocomplete="new-password"></label>
<label>Confirmar nova senha<input type="password" name="confirmar_senha" required minlength="8" autocomplete="new-password"></label>
<button type="submit">Alterar senha</button>
</form>
<?php require __DIR__ . '/_bottom.php'; ?>
