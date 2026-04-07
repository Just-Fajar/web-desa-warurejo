/**
 * Bootstrap JavaScript
 * 
 * Setup global configurations untuk libraries yang dipakai di seluruh aplikasi
 * 
 * Axios Configuration:
 * - HTTP client untuk AJAX requests ke backend
 * - Auto-inject CSRF token di setiap request
 * - Set 'X-Requested-With: XMLHttpRequest' header untuk Laravel detect AJAX
 * 
 * Usage Examples:
 * ```javascript
 * // GET request
 * axios.get('/api/berita').then(response => {
 *     console.log(response.data);
 * });
 * 
 * // POST request (CSRF token auto-injected)
 * axios.post('/admin/berita', {
 *     judul: 'Berita Baru',
 *     konten: 'Isi berita...'
 * }).then(response => {
 *     console.log(response.data);
 * });
 * 
 * // DELETE request
 * axios.delete('/admin/berita/1').then(response => {
 *     console.log('Deleted');
 * });
 * ```
 * 
 * CSRF Protection:
 * Laravel akan auto-verify CSRF token di setiap POST/PUT/DELETE request
 * Token di-inject otomatis dari meta tag csrf-token di head
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
