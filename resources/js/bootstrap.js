import axios from 'axios';
window.Axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';