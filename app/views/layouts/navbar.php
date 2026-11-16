<?php
// Navegação única para todas as telas internas do GymProgress.
$navRoute = $_GET['route'] ?? '';
$activeNav = str_starts_with((string)$navRoute, 'perfil') ? 'perfil' :
    (str_starts_with((string)$navRoute, 'fichas') ? 'fichas' :
    (str_starts_with((string)$navRoute, 'biblioteca') ? 'biblioteca' :
    (str_starts_with((string)$navRoute, 'treinar') ? 'treinar' :
    ($navRoute === 'dashboard' ? 'dashboard' : ''))));
?>
<nav class="gp-bottom-nav" aria-label="Navegação principal">
<a href="<?= e(routeUrl('dashboard')) ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg><small>Início</small></a>
<a href="<?= e(routeUrl('fichas')) ?>" <?= $activeNav === 'fichas' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h6"/></svg><small>Fichas</small></a>
<a href="<?= e(routeUrl('treinar')) ?>" <?= $activeNav === 'treinar' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/></svg><small>Treinar</small></a>
<a href="<?= e(routeUrl('biblioteca')) ?>" <?= $activeNav === 'biblioteca' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h5v16H4zM10 4h5v16h-5zM16 4h4v16h-4z"/></svg><small>Biblioteca</small></a>
<a href="<?= e(routeUrl('perfil')) ?>" <?= $activeNav === 'perfil' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-5 3-8 8-8s8 3 8 8"/></svg><small>Perfil</small></a>
</nav>
