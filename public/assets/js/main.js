/* GymProgress PWA v1 — registro progressivo, sem dependência para os treinos. */
(() => {
  if (!('serviceWorker' in navigator) || !window.isSecureContext) return;
  window.addEventListener('load', () => {
    // URL relativa ao próprio documento em /public; escopo também limitado a /public/.
    navigator.serviceWorker.register('service-worker.js', { scope: './' })
      .catch(error => console.warn('[GymProgress] Service Worker indisponível:', error));
  });
})();
