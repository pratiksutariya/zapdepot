import Integration from '@/views/user/integration/integration.vue'
import Zaps from '@/views/user/zap/index.vue'
import ZapDetail from '@/views/user/zap/zapDetail.vue'
import Log from '@/views/user/zap/log.vue'
import LogLists from '@/views/user/zap/logs.vue'
import GoAdd from '@/views/user/integration/integrationAdd.vue'

export default [{
        path: '/connect-integration',
        name: 'connectIntegration',
        component: Integration,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    },
    {
        path: '/zaps',
        name: 'Zaps',
        component: Zaps,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    },
    {
        path: '/zaps-detail/:id',
        name: 'ZapsDetail',
        component: ZapDetail,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    },
    {
        path: '/zaps-log',
        name: 'Log',
        component: Log,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    },
    {
        path: '/zap-logs/:id',
        name: 'LogLists',
        component: LogLists,
        props : true,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    },
    {
        path: '/integration/add/:type',
        name: 'GoAdd',
        component: GoAdd,
        meta: {
            requiresAuth: true,
            isUser: true
        },
    }

];