import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite'; // 1. Added Tailwind v4 Plugin
import { fileURLToPath, URL } from 'node:url'; // Modern way to handle paths

export default defineConfig({
    plugins: [
        tailwindcss(), // 2. MUST remain at the top
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // Modern, cleaner alias definitions
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '~': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@components': fileURLToPath(new URL('./resources/js/Components', import.meta.url)),
            '@jetstream': fileURLToPath(new URL('./resources/js/Jetstream', import.meta.url)),
            '@layouts': fileURLToPath(new URL('./resources/js/Layouts', import.meta.url)),
            '@pages': fileURLToPath(new URL('./resources/js/Pages', import.meta.url)),
        },
        extensions: ['.js', '.vue', '.json', '.ts'],
    },
    server: {
        // host: 'localhost' is default, but explicit is fine
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
});