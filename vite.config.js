import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    plugins: [
        vue(),
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    server: {
        host: '0.0.0.0',       // Allow access from outside the container
        port: 5173,            // Use the same port as defined in Docker Compose file
        hmr: {
            host: 'localhost', // Use 'localhost' for HMR (Hot Module Replacement)
        },
    }
});
