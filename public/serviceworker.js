self.addEventListener('push', function (event) {
    const data = event.data.json();

    // Customize notification based on dynamic data
    const options = {
        body: data.body,
        icon: data.icon || '/icon.png',
        badge: '/badge.png',
        data: {
            url: data.url,
            user_id: data.user_id,
            custom_data: data.custom_data || null
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    // Handle click based on custom data
    const notificationData = event.notification.data;
    let url = notificationData.url;

    // Example: Custom handling based on notification type
    if (notificationData.custom_data && notificationData.custom_data.order_id) {
        url = '/orders/' + notificationData.custom_data.order_id;
    }

    event.waitUntil(
        clients.openWindow(url)
    );
});