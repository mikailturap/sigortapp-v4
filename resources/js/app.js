import './bootstrap';

// Bootstrap'ı modül olarak içe aktar ve satır içi scriptler için global olarak aç (örn. new bootstrap.Modal(...))
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
