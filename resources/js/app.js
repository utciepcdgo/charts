import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import VueApexCharts from "vue3-apexcharts";
import {router} from '@inertiajs/vue3'
import {useLoading} from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueApexCharts)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: false,
});

const $loading = useLoading({
    canCancel: false,
    isFullPage: true,
    opacity: 0.7,
    color: "#7723ec",
});

let loader;

router.on("start", () => {
    loader = $loading.show();
})

router.on("finish", () => {
    loader.hide();
})
