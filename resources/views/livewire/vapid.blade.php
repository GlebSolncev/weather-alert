<div>
    @push('scripts')
        <script>
            if ('Notification' in window && 'serviceWorker' in navigator) {
                navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);

                    Notification.requestPermission().then(function (permission) {
                        if (permission === 'granted') {
                            registration.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: '{{ $vapidPublicKey }}'
                            }).then(function (subscription) {
                                let publicKey = btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh'))));
                                let authToken = btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth'))));

                                // if(localStorage.getItem('push-auth-token') === authToken) {
                                //     return;
                                // }

                                window.Livewire.dispatch('init-token', {
                                    endpoint: subscription.endpoint,
                                    publicKey: publicKey,
                                    authToken: authToken,

                                });

                                // localStorage.setItem('push-auth-token', authToken);
                            }).catch(function (err) {
                                console.log('Failed to subscribe the user: ', err);
                            });
                        }
                    });
                }).catch(function (error) {
                    console.error('Service Worker registration failed:', error);
                });
            }

        </script>
    @endpush
</div>