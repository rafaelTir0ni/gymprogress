<?php $pageTitle = 'Meu perfil'; require __DIR__ . '/_top.php'; ?>
<div class="perfil-heading"><p class="perfil-label">SUA CONTA</p><h1>Meu perfil</h1><p class="perfil-muted">Gerencie seus dados e preferências.</p></div>
<?php if ($notice !== ''): ?><p class="perfil-alert" role="status"><?= e($notice) ?></p><?php endif; ?>
<section class="perfil-card perfil-person"><span class="perfil-avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr(trim($account['nome']), 0, 1))) ?></span><div><h2><?= e($account['nome']) ?></h2><p><?= e($account['email']) ?></p></div></section>
<section class="perfil-card perfil-menu" aria-label="Opções do perfil">
<a href="<?= e(routeUrl('perfil-editar')) ?>">Editar nome e e-mail <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('perfil-seguranca')) ?>">Alterar senha <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('perfil-configuracoes')) ?>">Configurações do aplicativo <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('historico')) ?>">Histórico de treinos <span aria-hidden="true">›</span></a>
<a href="<?= e(routeUrl('metas')) ?>">Metas e conquistas <span aria-hidden="true">›</span></a>
</section>
<form action="<?= e(routeUrl('logout')) ?>" method="post" class="perfil-logout"><input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>"><button type="submit">Sair da conta</button></form>
<?php require __DIR__ . '/_bottom.php'; ?>
