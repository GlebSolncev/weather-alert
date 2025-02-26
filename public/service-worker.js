self.addEventListener('push', function(event) {
    data = JSON.parse(event.data.text())

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
        })
    );
});
