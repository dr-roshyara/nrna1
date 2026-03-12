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
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '~': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
        extensions: ['.js', '.vue', '.json'],
    },
    build: {
        // CRITICAL: Force Unix-style paths in manifest
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                // Force forward slashes in all generated paths
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
                // Ensure consistent formatting
                compact: true,
                generatedCode: {
                    constBindings: true,
                    objectShorthand: true,
                },
            },
        },
        // Ensure consistent chunking
        commonjsOptions: {
            include: [/node_modules/],
            transformMixedEsModules: true,
        },
        sourcemap: false,
        minify: 'esbuild',
        target: 'es2020',
    },
    // Force Unix line endings in generated files
    optimizeDeps: {
        esbuildOptions: {
            target: 'es2020',
            supported: {
                'bigint': true
            },
        },
    },
});