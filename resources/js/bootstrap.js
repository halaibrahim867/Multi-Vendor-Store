/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
 import echo from 'laravel-echo';

 import pusher from 'pusher-js';
 window.pusher = pusher;

 window.echo = new echo({
     broadcaster: 'pusher',
     key: import.meta.env.vite_pusher_app_key,
     cluster: import.meta.env.vite_pusher_app_cluster ?? 'mt1',
     wshost: import.meta.env.vite_pusher_host ? import.meta.env.vite_pusher_host : `ws-${import.meta.env.vite_pusher_app_cluster}.pusher.com`,
     wsport: import.meta.env.vite_pusher_port ?? 80,
     wssport: import.meta.env.vite_pusher_port ?? 443,
     forcetls: (import.meta.env.vite_pusher_scheme ?? 'https') === 'https',
    enabledtransports: ['ws', 'wss'],
 });
