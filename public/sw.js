self.addEventListener('push', function(event) {
    const data = event.data.json();
    const options = {
        body: data.body,
        data: data.data,
    };
    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/')
    );
});