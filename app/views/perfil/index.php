<?php $pageTitle = 'Meu perfil'; require __DIR__ . '/_top.php'; ?>
<div class="perfil-heading"><p class="perfil-label">SUA CONTA</p><h1>Meu perfil</h1><p class="perfil-muted">Gerencie seus dados e preferências.</p></div>
<?php if ($notice !== ''): ?><p class="perfil-alert" role="status"><?= e($notice) ?></p><?php endif; ?>
<section class="perfil-card perfil-person"><?= profileAvatarMarkup($account['foto_perfil'] ?? null, (string) $account['nome'], 'perfil-avatar') ?><div><h2><?= e($account['nome']) ?></h2><p><?= e($account['email']) ?></p></div></section>
<section class="perfil-card perfil-photo-settings" aria-label="Foto do perfil">
<h2>Foto de perfil</h2><p class="perfil-muted">Selecione uma foto da galeria. Formatos JPEG, PNG ou WebP (até 3 MB).</p>
<form method="post" action="<?= e(routeUrl('perfil-foto-salvar')) ?>" enctype="multipart/form-data" class="perfil-photo-form" id="gp-photo-form">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
<label for="foto">Escolher foto</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" required>
<p class="perfil-muted gp-photo-hint">Escolha uma imagem e ajuste o zoom e a posição antes de salvar.</p>
<button type="submit" id="gp-photo-fallback">Salvar foto</button>
</form>
<dialog id="gp-crop-dialog" class="gp-crop-dialog" aria-labelledby="gp-crop-title">
  <div class="gp-crop-content">
    <h2 id="gp-crop-title">Ajustar foto</h2>
    <p>Arraste para posicionar. Use o controle para aproximar ou afastar.</p>
    <div class="gp-crop-preview"><canvas id="gp-crop-canvas" width="512" height="512" aria-label="Prévia da foto quadrada"></canvas></div>
    <label for="gp-crop-zoom">Zoom</label>
    <input id="gp-crop-zoom" type="range" min="1" max="3" step="0.01" value="1">
    <p class="gp-crop-error" id="gp-crop-error" role="alert" hidden></p>
    <div class="gp-crop-actions"><button type="button" id="gp-crop-cancel">Cancelar</button><button type="button" id="gp-crop-save">Aplicar e salvar</button></div>
  </div>
</dialog>
<script src="assets/js/perfil-foto.js" defer></script>
<?php if (profileImageName($account['foto_perfil'] ?? null)): ?>
<form method="post" action="<?= e(routeUrl('perfil-foto-remover')) ?>" class="perfil-remove-photo">
<input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><button type="submit">Remover foto</button>
</form>
<?php endif; ?>
</section>
<section class="perfil-card perfil-menu" aria-label="Opções do perfil">
<a href="<?= e(routeUrl('perfil-editar')) ?>">Editar nome e e-mail <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('perfil-seguranca')) ?>">Alterar senha <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('perfil-configuracoes')) ?>">Configurações do aplicativo <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('historico')) ?>">Histórico de treinos <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('metas')) ?>">Metas e conquistas <span aria-hidden="true">›</span></a>
</section>
<form action="<?= e(routeUrl('logout')) ?>" method="post" class="perfil-logout"><input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><button type="submit">Sair da conta</button></form>
<?php require __DIR__ . '/_bottom.php'; ?>
