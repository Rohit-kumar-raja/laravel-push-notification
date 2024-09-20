import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'de90751e4bfb7b0e7358',
    cluster: 'ap2',
    encrypted: true
});

// Assuming you have userId as a JS variable (you can pass it via Blade)
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        console.log(notification.message);

        // Show a browser notification
        if (Notification.permission === 'granted') {
            new Notification(notification.message);
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification(notification.message);
                }
            });
        }
    });

if (Notification.permission !== 'granted') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            console.log('Notifications enabled');
        } else {
            console.log('Notifications disabled');
        }
    });
}


