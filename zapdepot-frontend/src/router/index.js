import { createWebHistory, createRouter } from "vue-router";
import Login from '@/views/auth/login.vue'
import Dashboard from '@/views/dashboard.vue'
import Home from '@/views/home.vue'
import ChangePassword from '@/views/auth/changePassword.vue'
import store from '@/store'
import MasterLayout from '@/components/layout/master.vue'
import MyAccount from '@/views/account/myaccount.vue'
import adminRoutes from '@/router/roles/admin.js';
import userRoutes from '@/router/roles/user.js';

const routes = [{
        path: '/',
        name: "homepage",
        component: Home,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: "/",
        component: MasterLayout,
        meta: {
            requiresAuth: true
        },
        children: [{
                path: '/dashboard',
                name: "Home",
                component: Dashboard,
                meta: {
                    requiresAuth: true
                },
            },
            {
                path: '/change-pass',
                name: "changePass",
                component: ChangePassword,
            },
            {
                path: '/my-account',
                name: "myAccount",
                component: MyAccount,
            },
            ...adminRoutes,
            ...userRoutes,
        ]
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            requiresAuth: false
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    base: 'http://zapdepot.io/',
    routes,
});
/* eslint-disable */
router.beforeEach(async(to, from, next) => {
        const isLoggedIn = store.state.auth.authenticated
        const requiresAuth = to.matched.some((record) => record.meta.requiresAuth)
        const isAdmin = to.matched.some((record) => record.meta.isAdmin)
        const isUser = to.matched.some((record) => record.meta.isUser)
        let user = store.state.auth.user;
        const { path, name, params } = to
        if (isLoggedIn && ['login', 'forgotPassword', 'forgotView'].includes(name)) {
            return next('/')
        } else if (requiresAuth && isLoggedIn && isAdmin && user.type != 'admin') {
            return next('/login')
        } else if (requiresAuth && isLoggedIn && isUser && user.type != 'user') {
            return next('/login')
        } else if (requiresAuth && !isLoggedIn) {
            return next('/login')
        }
        return next()
    })
    /* eslint-enable */

export default router;
