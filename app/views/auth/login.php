<?php $pageTitle = 'Entrar'; $pageDescription = 'Acesse seus treinos e acompanhe sua evolução.'; require dirname(__DIR__) . '/layouts/header.php'; ?>
<?php if ($notice !== ''): ?><p class="notice" role="status"><?= e($notice) ?></p><?php endif; ?>
<?php if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" action="<?= e(routeUrl('login')) ?>">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
  <label for="email">E-mail</label>
  <input id="email" type="email" name="email" autocomplete="username" maxlength="190" required value="<?= e(postValue('email')) ?>">
  <label for="senha">Senha</label>
  <input id="senha" type="password" name="senha" autocomplete="current-password" required>
  <button type="submit">Entrar</button>
</form>
<p class="alt">Ainda não tem uma conta? <a href="<?= e(routeUrl('cadastro')) ?>">Cadastre-se agora</a></p>
<p class="muted">Recuperação de senha por e-mail: integração pendente.</p>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
