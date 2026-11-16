<?php $pageTitle = $pageTitle ?? 'Perfil'; ?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#0b1010"><meta name="color-scheme" content="dark"><title><?= e($pageTitle) ?> | GymProgress</title><link rel="stylesheet" href="assets/css/perfil.css"><link rel="stylesheet" href="assets/css/style.css"><link rel="manifest" href="manifest.webmanifest">
<link rel="icon" type="image/png" sizes="192x192" href="assets/img/icons/icon-192.png">
<link rel="apple-touch-icon" href="assets/img/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<script src="assets/js/main.js" defer></script>
</head><body>
<header class="perfil-top"><a href="<?= e(routeUrl('dashboard')) ?>" class="perfil-brand"><span class="perfil-brand-icon">G</span> GymProgress</a><span>Perfil</span></header>
<main class="perfil-main">
