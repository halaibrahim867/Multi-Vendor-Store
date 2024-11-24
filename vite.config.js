import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/cart.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // Ensure this matches the default Laravel Vite config
        assetsDir: '', // To avoid additional nesting of folders in 'build'
    },
});
