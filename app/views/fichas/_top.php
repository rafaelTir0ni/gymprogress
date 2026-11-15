<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? 'Fichas';
?><!doctype html>
<html lang="pt-BR"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#0b1010"><meta name="color-scheme" content="dark">
<title><?= e($pageTitle) ?> | GymProgress</title>
<link rel="stylesheet" href="assets/css/fichas.css">
</head><body><header class="topbar"><a class="brand" href="<?= e(routeUrl('dashboard')) ?>"><span class="brand-symbol">G</span> GymProgress</a><span class="top-label">Minhas fichas</span></header>
<main class="screen">
