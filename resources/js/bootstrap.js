/**
 * Bootstrap JavaScript
 * 
 * Setup global configurations untuk libraries yang dipakai di seluruh aplikasi
 * 

 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
