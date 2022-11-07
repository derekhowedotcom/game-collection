import './bootstrap';
//import { createApp } from 'vue';
//this is the way we need to import vue due to vite?
import { createApp, onMounted } from 'vue/dist/vue.esm-bundler';

import laravelVuePagination from 'laravel-vue-pagination';
import router from './routes/index'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import useAuth from './composables/auth'
import { abilitiesPlugin } from '@casl/vue';
import ability from './services/ability';

const app = createApp({
    setup(){
        const { getUser } = useAuth()
        onMounted(getUser)
    }
})
app.use(router)
app.use(VueSweetalert2)
app.use(abilitiesPlugin, ability)
app.component('Pagination', laravelVuePagination)
app.mount('#app')
