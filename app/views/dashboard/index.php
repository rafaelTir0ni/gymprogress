<?php $pageTitle='Início'; $pageDescription='Seu espaço de treinos.'; require dirname(__DIR__) . '/layouts/header.php'; ?>
<p class="notice" role="status">Olá, <?= e((string)($_SESSION['usuario_nome'] ?? 'atleta')) ?>! Você entrou com sucesso.</p>
<p class="muted">O Dashboard completo será implementado nos próximos módulos.</p>
<form method="post" action="<?= e(routeUrl('logout')) ?>">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
  <button type="submit" class="secondary">Sair da conta</button>
</form>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
