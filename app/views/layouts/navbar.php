<?php
// Navegação única para todas as telas internas do GymProgress.
$navRoute = $_GET['route'] ?? '';
$navSection = explode('-', $navRoute, 2)[0];
$activeNav = match ($navSection) {
    'historico', 'metas' => 'perfil',
    default => $navSection,
};
?>
<nav class="gp-bottom-nav" aria-label="Navegação principal">
<a href="<?= e(routeUrl('dashboard')) ?>" <?= $activeNav === 'dashboard' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg><small>Início</small></a>
<a href="<?= e(routeUrl('fichas')) ?>" <?= $activeNav === 'fichas' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2M8 11h8m-8 4h8m-8 4h5"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg><small>Fichas</small></a>
<a href="<?= e(routeUrl('treinar')) ?>" <?= $activeNav === 'treinar' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 12h8M1 12h2m18 0h2"/><rect x="3" y="8" width="2.5" height="8" rx="1"/><rect x="5.5" y="5" width="2.5" height="14" rx="1"/><rect x="16" y="5" width="2.5" height="14" rx="1"/><rect x="18.5" y="8" width="2.5" height="8" rx="1"/></svg><small>Treinar</small></a>
<a href="<?= e(routeUrl('biblioteca')) ?>" <?= $activeNav === 'biblioteca' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6C9 4 5 4 2 5v15c3-1 7-1 10 1 3-2 7-2 10-1V5c-3-1-7-1-10 1Zm0 0v15"/></svg><small>Biblioteca</small></a>
<a href="<?= e(routeUrl('perfil')) ?>" <?= $activeNav === 'perfil' ? 'aria-current="page"' : '' ?>><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-5 3-8 8-8s8 3 8 8"/></svg><small>Perfil</small></a>
</nav>
