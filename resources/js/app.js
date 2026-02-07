require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const el = document.getElementById('app');
const initialPage = JSON.parse(el.dataset.page);

// Inject server locale into window BEFORE importing i18n
// This ensures i18n initializes with the correct locale from the server
if (initialPage.props && initialPage.props.locale) {
    window.__initialLocale = initialPage.props.locale;
}

// NOW import i18n after window.__initialLocale is set
import i18n from './i18n';

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: initialPage,
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(i18n)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
