import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // CRITICAL for Vite v6
            buildDirectory: 'build',
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
    // Vite v6 requires explicit manifest configuration
    build: {
        manifest: 'manifest.json',  // NOT just true - use string filename
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
                // Force chunk splitting to avoid large files
                manualChunks: {
                    vendor: ['vue', '@inertiajs/vue3', 'vue-i18n'],
                },
            },
        },
        // Ensure sourcemaps don't block manifest generation
        sourcemap: false,
        minify: 'esbuild',
        target: 'es2020',
    },
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '~': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
        extensions: ['.js', '.vue', '.json'],
    },
    // Server config for dev
    server: {
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
});