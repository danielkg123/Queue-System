import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',  // Use 'local' as the key (same as in .env)
    cluster: 'mt1',
    wsHost: window.location.hostname,  // Local WebSocket host
    wsPort: 3000,  // Port where the WebSocket server is running
    disableStats: true,
    forceTLS: false  // Disable TLS for local development
});


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
