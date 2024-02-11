import { createApp } from 'vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './App.vue'
import router from './router'
import store from './store'
import loader from "./components/loader/loader";
import Pagination from 'v-pagination-3';
const options = {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true
};
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
const app = createApp(App)
app.use(Toast, options);
app.use(router);
app.component('pagination', Pagination);
app.component("loader", loader);
app.use(store);
app.use(VueSweetalert2);
app.mount('#app')
