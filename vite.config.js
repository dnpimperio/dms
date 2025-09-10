import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

const serveRootIndexPlugin = {
    name: 'serve-root-index',
    apply: 'serve',
    enforce: 'pre',
    configureServer(server) {
        server.middlewares.use(async (req, res, next) => {
            if (req.url === '/' || req.url === '') {
                try {
                    const filePath = path.resolve(process.cwd(), 'index.html');
                    const rawHtml = fs.readFileSync(filePath, 'utf-8');
                    const html = await server.transformIndexHtml('/', rawHtml);
                    res.setHeader('Content-Type', 'text/html');
                    res.statusCode = 200;
                    res.end(html);
                    return;
                } catch (e) {
                    // Fall back if reading index.html fails
                }
            }
            next();
        });
    },
};

export default defineConfig({
    plugins: [
        serveRootIndexPlugin,
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
