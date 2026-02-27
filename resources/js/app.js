import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Recommended way for Vue 3
import i18n from './i18n';

createInertiaApp({
    id: 'app',
    title: (title) => title ? `${title} - Public Digit` : 'Public Digit',
    
    // Modern Vite globbing
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),

    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Inject server locale to global window before mounting
        if (props.initialPage.props.locale) {
            window.__initialLocale = props.initialPage.props.locale;
        }

        app.use(plugin)
           .use(i18n)
           .use(ZiggyVue) // Modern way: makes route() available in templates & scripts
           .mount(el);
    },

    // In Inertia 2.0, progress is a configuration object here.
    // The separate InertiaProgress.init() is no longer used.
    progress: {
        color: '#4B5563',
        showSpinner: false,
    },
});

// Note: Inertia 2.0 handles SSR context better. 
// If you truly want to force-disable it on the client:
window.__INERTIA_SSR_DISABLED = true;