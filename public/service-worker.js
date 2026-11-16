/* GymProgress PWA v1 — páginas e dados pessoais nunca entram no cache. */
const CACHE_NAME = "gymprogress-static-v3-dashboard-20261116";
const FILES = [
  "offline.html",
  "manifest.webmanifest",
  "assets/css/auth.css",
  "assets/css/biblioteca.css",
  "assets/css/dashboard.css",
  "assets/css/exercicios.css",
  "assets/css/fichas.css",
  "assets/css/historico.css",
  "assets/css/metas.css",
  "assets/css/perfil.css",
  "assets/css/sessoes.css",
  "assets/css/style.css",
  "assets/js/biblioteca.js",
  "assets/js/dashboard.js",
  "assets/js/estatisticas.js",
  "assets/js/fichas.js",
  "assets/js/historico.js",
  "assets/js/main.js",
  "assets/js/metas.js",
  "assets/js/treino.js",
  "assets/img/icons/icon-192.png",
  "assets/img/icons/icon-512.png",
  "assets/img/icons/icon-maskable-512.png"
];
const BASE = new URL(self.registration.scope);
const ALLOWED = new Set(FILES.map(path => new URL(path, BASE).href));
const OFFLINE_URL = new URL('offline.html', BASE).href;

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll([...ALLOWED]))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil((async () => {
    const names = await caches.keys();
    await Promise.all(names.filter(name => name.startsWith('gymprogress-static-') && name !== CACHE_NAME)
      .map(name => caches.delete(name)));
    await self.clients.claim();
  })());
});

self.addEventListener('fetch', event => {
  const request = event.request;
  if (request.method !== 'GET') return; // POSTs, inclusive registros de treinos, passam direto à rede.
  const url = new URL(request.url);
  if (url.origin !== self.location.origin) return;
  if (!url.pathname.startsWith(BASE.pathname)) return;

  if (request.mode === 'navigate') {
    // Documentos autenticados e respostas PHP são sempre network-only.
    event.respondWith(fetch(request).catch(async () => {
      return (await caches.match(OFFLINE_URL)) || Response.error();
    }));
    return;
  }

  if (!ALLOWED.has(url.href)) return; // Nunca armazenar endpoints com dados privados.
  event.respondWith((async () => {
    const cached = await caches.match(request);
    if (cached) return cached;
    const response = await fetch(request);
    if (response.ok && response.type === 'basic') {
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, response.clone());
    }
    return response;
  })());
});
