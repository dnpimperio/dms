import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const rootRedirectPlugin = {
    name: 'root-redirect-to-index',
    apply: 'serve',
    enforce: 'pre',
    configureServer(server) {
        server.middlewares.use((req, res, next) => {
            if (req.url === '/' || req.url === '') {
                res.statusCode = 302;
                res.setHeader('Location', '/index.html');
                res.end();
                return;
            }
            next();
        });
    },
};

export default defineConfig({
    plugins: [
        rootRedirectPlugin,
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
            }
        }
    },
});
