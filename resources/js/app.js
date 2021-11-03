require('./bootstrap')

import { createApp } from 'vue'
import '../css/main.css'
import '../css/app.css'
import App from './App.vue'
import mitt from 'mitt';

window.mitter = mitt();

createApp(App).mount('#app')
