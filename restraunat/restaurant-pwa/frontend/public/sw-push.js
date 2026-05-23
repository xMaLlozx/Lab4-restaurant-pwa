// public/sw-push.js
// Дополнительный Service Worker для Push-уведомлений
// Регистрируется вместе с основным Workbox SW

self.addEventListener('push', function (event) {
  if (!event.data) return

  let data
  try { data = event.data.json() } catch { data = { title: 'Ресторан', body: event.data.text() } }

  const options = {
    body:    data.body || '',
    icon:    data.icon || '/icons/icon-192x192.png',
    badge:   '/icons/icon-72x72.png',
    vibrate: [200, 100, 200],
    tag:     'restaurant-notification',
    renotify: true,
    data: { url: data.url || '/' },
    actions: [
      { action: 'open',    title: 'Открыть' },
      { action: 'dismiss', title: 'Закрыть' },
    ],
  }

  event.waitUntil(
    self.registration.showNotification(data.title || 'Ресторан', options)
  )
})

self.addEventListener('notificationclick', function (event) {
  event.notification.close()

  if (event.action === 'dismiss') return

  const url = event.notification.data?.url || '/'
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(list => {
      const existing = list.find(c => c.url.includes(url))
      if (existing) return existing.focus()
      return clients.openWindow(url)
    })
  )
})
