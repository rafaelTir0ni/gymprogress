<?php $pageTitle = 'Criar conta'; $pageDescription = 'Comece a registrar sua evolução.'; require dirname(__DIR__) . '/layouts/header.php'; ?>
<?php if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" action="<?= e(routeUrl('cadastro')) ?>">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
  <label for="nome">Nome</label>
  <input id="nome" type="text" name="nome" autocomplete="name" maxlength="120" required value="<?= e($name) ?>">
  <label for="email">E-mail</label>
  <input id="email" type="email" name="email" autocomplete="email" maxlength="190" required value="<?= e($email) ?>">
  <label for="senha">Senha <span class="hint">(mínimo 8 caracteres)</span></label>
  <input id="senha" type="password" name="senha" minlength="8" autocomplete="new-password" required>
  <label for="confirmar_senha">Confirmar senha</label>
  <input id="confirmar_senha" type="password" name="confirmar_senha" minlength="8" autocomplete="new-password" required>
  <button type="submit">Criar conta</button>
</form>
<p class="alt">Já tem uma conta? <a href="<?= e(routeUrl('login')) ?>">Entrar</a></p>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
