import ManageUser from '@/views/admin/manageUser.vue'
import ManageUserAdd from '@/views/admin/manageUserAdd.vue'

export default [{
        path: '/manager-user',
        name: 'ManageUser',
        component: ManageUser,
        meta: {
            requiresAuth: true,
            isAdmin: true
        },

    },
    {
        path: '/manager-user-add',
        name: 'ManageUserAdd',
        component: ManageUserAdd,
        meta: {
            requiresAuth: true,
            isAdmin: true
        }
    },
    {
        path: '/manager-user-edit/:id',
        name: 'ManageUserEdit',
        component: ManageUserAdd,
        meta: {
            requiresAuth: true,
            isAdmin: true
        }
    }
];