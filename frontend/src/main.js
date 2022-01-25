import 'mdb-vue-ui-kit/css/mdb.min.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VueBasicAlert from 'vue-basic-alert'

const app = createApp(App)
    .use(router)
    .use(VueBasicAlert);

app.mount('#app');