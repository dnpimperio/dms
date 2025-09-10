import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default defineConfig({
    plugins: [
        // Custom middleware to serve our index.html at '/'
        {
            name: 'serve-root-index',
            configureServer(server) {
                server.middlewares.use(async (req, res, next) => {
                    if (req.url === '/' || req.url === '/index.html') {
                        try {
                            const indexPath = path.resolve(__dirname, 'index.html');
                            const html = await server.transformIndexHtml('/', fs.readFileSync(indexPath, 'utf-8'));
                            res.setHeader('Content-Type', 'text/html');
                            res.end(html);
                            return;
                        } catch (e) {
                            return next(e);
                        }
                    }
                    return next();
                });
            },
        },
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
