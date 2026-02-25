import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
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
            '@': path.resolve(__dirname, 'resources/js'),
            '@components': path.resolve(__dirname, 'resources/js/Components'),
            '@jetstream': path.resolve(__dirname, 'resources/js/Jetstream'),
            '@layouts': path.resolve(__dirname, 'resources/js/Layouts'),
            '@pages': path.resolve(__dirname, 'resources/js/Pages'),
            '~': path.resolve(__dirname, 'resources/js'),
        },
        extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue'],
    },
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
    },
});