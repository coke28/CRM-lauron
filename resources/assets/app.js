import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

console.log(process.env);

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    // wssPort: 6001,
    forceTLS: false,
    disableStats: true,
    // enabledTransports: ['ws', 'wss'],
});
