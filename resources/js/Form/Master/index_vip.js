import {
    createApp
} from 'vue';
import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import App from '../Page/master_page.vue'
import {
    createRouter,
    createWebHistory
} from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import vSelect from 'vue-select'
import "vue-select/dist/vue-select.css";
import '../../../css/regular-form-theme.css';


const routes = [{
    path: '/:pathMatch(.*)*',
    component: () => import('../Page/vip.vue'),
    name: 'vip',
    meta: {
        title: 'PT Global Expo Management'
    }
}, ];

const router = new createRouter({
    routes,
    history: createWebHistory()
});
router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next()
});
const app = createApp(App);

window.bootstrap = bootstrap;

app.use(router);
app.use(VueSweetalert2);
app.component('v-select', vSelect);
app.mount('#app');
