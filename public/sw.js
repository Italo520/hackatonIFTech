importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.4.1/workbox-sw.js');

if (workbox) {
  // Precache app shell and basic fallback UI
  workbox.precaching.precacheAndRoute([
    {url: '/', revision: '1'},
    {url: '/mapa', revision: '1'},
    {url: '/manifest.json', revision: '1'}
  ]);

  // API Caching strategy - NetworkFirst for dynamic data
  workbox.routing.registerRoute(
    new RegExp('/api/v1/'),
    new workbox.strategies.NetworkFirst({
      cacheName: 'api-cache',
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 50,
          maxAgeSeconds: 24 * 60 * 60, // 1 Day
        }),
      ],
    })
  );

  // Images caching strategy
  workbox.routing.registerRoute(
    ({request}) => request.destination === 'image',
    new workbox.strategies.CacheFirst({
      cacheName: 'images-cache',
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 100,
          maxAgeSeconds: 7 * 24 * 60 * 60, // 7 Days
        }),
      ],
    })
  );
  
  // Register sync for offline writes
  const bgSyncPlugin = new workbox.backgroundSync.BackgroundSyncPlugin('offline-queue', {
    maxRetentionTime: 24 * 60 // Retry for max of 24 Hours (specified in minutes)
  });
  
  workbox.routing.registerRoute(
    /\/api\/v1\/(avaliacoes|ocorrencias)/,
    new workbox.strategies.NetworkOnly({
      plugins: [bgSyncPlugin]
    }),
    'POST'
  );
}
