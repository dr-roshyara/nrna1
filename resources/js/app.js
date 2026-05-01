import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Recommended way for Vue 3
import i18n from './i18n';
import { useGeoLocation } from './composables/useGeoLocation';
import { useLocaleDebug } from './composables/useLocaleDebug';

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

        // 🔴 CRASH ON VUE WARNINGS IN DEVELOPMENT
        // Any missing property, undefined computed, or template error will throw
        // This catches bugs immediately instead of silently failing in production
        if (import.meta.env.DEV) {
            app.config.warnHandler = (msg, instance, trace) => {
                console.error('❌ Vue Warning (will crash in dev):', msg);
                if (trace) console.error('Trace:', trace);
                throw new Error(`Vue Warning: ${msg}`);
            };
        }

        // Set i18n locale from server-provided locale (page.props.locale)
        // Must be done before mount so useMeta and other composables see the right locale
        const serverLocale = props.initialPage.props.locale;
        if (serverLocale && ['de', 'en', 'np'].includes(serverLocale)) {
            i18n.global.locale.value = serverLocale;
        }

        app.use(plugin)
           .use(i18n)
           .use(ZiggyVue) // Modern way: makes route() available in templates & scripts
           .mount(el);

        // Initialize debug utilities (available in browser console during development)
        useLocaleDebug();

        // 🌍 Auto-detect user locale from geo-location (fire-and-forget, non-blocking)
        // Always detect, but respect manual choice via LanguageSwitcher (which sets cookie)
        // Get CSRF token from XSRF-TOKEN cookie (Laravel sets this automatically)
        const getCsrfToken = () => {
            const name = 'XSRF-TOKEN='
            const decodedCookie = decodeURIComponent(document.cookie)
            const cookieArr = decodedCookie.split(';')
            for (let cookie of cookieArr) {
                cookie = cookie.trim()
                if (cookie.indexOf(name) === 0) {
                    return decodeURIComponent(cookie.substring(name.length))
                }
            }
            return ''
        }

        fetch('/api/detect-location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            })
        })
        .then(res => res.json())
        .then(data => {
            const locale = data.locale
            if (locale) {
                // Check if user manually set language (LanguageSwitcher sets this cookie)
                const cookieLocale = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('locale='))
                    ?.split('=')[1]

                // Only apply geo-detected locale if no manual cookie choice exists
                if (!cookieLocale) {
                    i18n.global.locale.value = locale
                    document.cookie = `locale=${locale};path=/;max-age=31536000`
                    console.log('✅ Geo-location auto-detected locale:', locale)
                }
            }
        })
        .catch(err => console.error('❌ Geo-detection failed:', err))
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