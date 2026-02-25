require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import route from 'ziggy-js';

const el = document.getElementById('app');
const initialPage = JSON.parse(el.dataset.page);

// Make route() available globally
window.route = route;

// Inject server locale
if (initialPage.props && initialPage.props.locale) {
    window.__initialLocale = initialPage.props.locale;
}

import i18n from './i18n';

createInertiaApp({
    id: 'app',
    title: (title) => `${title} - Public Digit`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue', { eager: true })
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .mixin({ methods: { route } })
            .mount(el);
    },
    progress: { color: '#4B5563' },
});

// Disable SSR - not needed for this app
if (window.__INERTIA_SSR_DISABLED === undefined) {
    window.__INERTIA_SSR_DISABLED = true;
}

InertiaProgress.init({ color: '#4B5563' });