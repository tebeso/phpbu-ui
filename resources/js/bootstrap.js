import _ from 'lodash';
import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from 'axios';
import Echo from 'laravel-echo';

window._ = _;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'broadcasting', // hard code
    wsHost: window.location.hostname,
    wssHost: window.location.hostname,
    enabledTransports: ['ws','wss'],
    wsPort:6001,
    wssPort:6001,
    forceTLS: false,
    disableStats: true,
    cluster: 'eu',
    encrypted: false,
});