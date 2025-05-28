import 'bootstrap';

import 'datatables.net-fixedheader-bs5';
import 'datatables.net-responsive-bs5';

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import $ from 'jquery';
window.$ = $;
