<?php
$pageTitle = 'Início';
$firstName = trim(explode(' ', trim((string) ($_SESSION['usuario_nome'] ?? 'Atleta')))[0]);
$duration = static function (int $seconds): string {
    $minutes = intdiv(max(0, $seconds), 60);
    return $minutes >= 60 ? intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'min' : $minutes . ' min';
};
?><!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#0b0f12">
<meta name="color-scheme" content="dark">
<title>Início | GymProgress</title>
<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/premium.css">

<link rel="manifest" href="manifest.webmanifest">
<link rel="icon" type="image/png" sizes="192x192" href="assets/img/icons/icon-192.png">
<link rel="apple-touch-icon" href="assets/img/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<script src="assets/js/main.js" defer></script>
</head>
<body class="premium premium-dashboard">
<header class="dash-topbar">
  <a class="dash-brand" href="<?= e(routeUrl('dashboard')) ?>"><span class="dash-logo">G</span><span>GymProgress</span></a>
  <a class="gp-avatar-link" href="<?= e(routeUrl('perfil')) ?>" aria-label="Abrir meu perfil" title="Meu perfil">
    <?= profileAvatarMarkup($profile['foto_perfil'] ?? null, (string) ($profile['nome'] ?? 'Atleta')) ?>
  </a>
</header>
<main class="dashboard-screen">
  <?php if ($notice): ?><p class="dash-notice" role="status"><?= e($notice) ?></p><?php endif; ?>
  <?php if ($achievementNotice): ?>
  <section class="dash-notice" role="status" aria-label="Nova conquista">
    <strong>Nova conquista: <?= e($achievementNotice['nome']) ?></strong>
    <p><?= e($achievementNotice['descricao']) ?></p>
    <div class="meta-banner-actions">
      <form method="post" action="<?= e(routeUrl('metas-aviso')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="id" value="<?= (int)$achievementNotice['id'] ?>">
        <input type="hidden" name="acao" value="ver">
        <button type="submit">Ver minhas metas →</button>
      </form>
      <form method="post" action="<?= e(routeUrl('metas-aviso')) ?>">
        <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="id" value="<?= (int)$achievementNotice['id'] ?>">
        <input type="hidden" name="acao" value="dispensar">
        <button type="submit">Dispensar</button>
      </form>
    </div>
  </section>
  <?php endif; ?>
  <section class="dash-welcome" aria-label="Boas-vindas">
    <h1>Olá, <?= e($firstName ?: 'Atleta') ?>!</h1>
    <a class="dash-button dash-primary" href="<?= e(routeUrl('treinar')) ?>">Iniciar treino</a>
  </section>

  <?php if ($active): ?>
  <section class="dash-active" aria-label="Treino em andamento">
    <div><span class="dash-state"><span class="dash-state-dot"></span> EM ANDAMENTO</span>
      <h2><?= e((string) $active['nome_ficha_snapshot']) ?></h2>
      <p>Iniciado em <?= e($displayDate($active['iniciada_em'])) ?></p>
    </div>
    <a class="dash-button dash-continue" href="<?= e(routeUrl('treinar-andamento') . '&id=' . (int) $active['id']) ?>">Continuar treino <span aria-hidden="true">→</span></a>
  </section>
  <?php endif; ?>

  <section class="dash-section" aria-labelledby="dash-frequencia">
    <div class="dash-section-title"><h2 id="dash-frequencia">Sua frequência</h2><span>Treinos finalizados</span></div>
    <div class="dash-stat-grid">
      <article class="dash-stat"><strong><?= (int) $counts['semana'] ?></strong><span>Esta semana</span><small>Desde segunda-feira</small></article>
      <article class="dash-stat"><strong><?= (int) $counts['mes'] ?></strong><span>Este mês</span><small><?= e($today->format('m/Y')) ?></small></article>
    </div>
  </section>

  <section class="dash-section" aria-labelledby="dash-ultimo">
    <div class="dash-section-title"><h2 id="dash-ultimo">Último treino</h2><a href="<?= e(routeUrl('historico')) ?>">Ver histórico</a></div>
    <?php if ($latest): ?>
    <article class="dash-latest">
      <span class="dash-complete">● FINALIZADO</span>
      <h3><?= e((string) $latest['nome_ficha_snapshot']) ?></h3>
      <p><?= e($displayDate($latest['finalizada_em'])) ?></p>
      <div class="dash-latest-metrics">
        <div><strong><?= e($duration((int) ($latest['duracao_segundos'] ?? 0))) ?></strong><span>Duração</span></div>
        <div><strong><?= (int) $latest['series_concluidas'] ?></strong><span>Séries concluídas</span></div>
      </div>
      <a class="dash-detail" href="<?= e(routeUrl('historico-detalhes') . '&id=' . (int) $latest['id']) ?>">Ver detalhes do treino <span aria-hidden="true">→</span></a>
    </article>
    <?php else: ?>
    <div class="dash-empty">
      <svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18m-12 5h6"/></svg>
      <p>Nenhum treino registrado</p>
    </div>
    <?php endif; ?>
  </section>
  <p class="dash-footer-note">Os indicadores consideram apenas treinos finalizados.</p>
</main>
<?php require dirname(__DIR__) . '/layouts/navbar.php'; ?>
</body>
</html>
