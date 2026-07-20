import {
    createApp
} from 'vue';
import App from '../Page/master_page.vue'
import {
    createRouter,
    createWebHistory
} from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


const routes = [{
    path: '/',
    component: () => import('../Page/reguler.vue'),
    name: 'reguler',
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
app.use(router);
app.use(VueSweetalert2);
app.mount('#app');
