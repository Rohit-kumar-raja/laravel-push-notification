<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- For CSRF token -->
    <title>Push Notification Example</title>
</head>
<body>

    <h1>Web Push Notification Example</h1>
    <p>Click "Allow" to enable notifications for this site.</p>

    <!-- Include the JavaScript for push notifications -->
    <script>
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/sw.js').then(function(swReg) {
                console.log('Service Worker is registered', swReg);
                subscribeUser(swReg);
            });
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        function subscribeUser(swReg) {
            const applicationServerKey = urlBase64ToUint8Array('BFGFEXhjjYDr4NTbb56E-oGxfLYbAtIjlhv9h7CW1ZMOQSBDnDpK5VnG3xZ0kGdrtCCkLJiTfPUPpRlLR8GOcMA'); // Replace with your VAPID public key

            swReg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            }).then(function(subscription) {
                console.log('User is subscribed:', subscription);
                sendSubscriptionToServer(subscription);
            }).catch(function(err) {
                console.log('Failed to subscribe the user: ', err);
            });
        }

        function sendSubscriptionToServer(subscription) {
            fetch('/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(subscription)
            }).then(function(response) {
                return response.json();
            }).then(function(data) {
                console.log('Subscription sent to server:', data);
            }).catch(function(error) {
                console.error('Error sending subscription:', error);
            });
        }
    </script>

</body>
</html>
