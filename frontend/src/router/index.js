import {createRouter, createWebHashHistory} from 'vue-router'
import Jobs from '../views/Jobs.vue'


const routes = [
    {
        path: '/',
        name: 'jobs',
        component: Jobs
    },
    {
        path: '/job/create',
        name: 'job-create',
        component: () => import("@/components/AddJobForm")
    },
    {
        path: '/setting/create',
        name: 'setting-create',
        component: () => import("@/components/AddSettingForm")
    },
    {
        path: '/job/template/create',
        name: 'job-template-create',
        component: () => import("@/components/AddJobTemplate")
    },
    {
        path: '/setting',
        name: 'settings',
        component: () => import("@/components/SettingsList")
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

export default router
