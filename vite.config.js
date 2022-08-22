import { defineConfig, splitVendorChunkPlugin } from 'vite'
import liveReload from 'vite-plugin-live-reload'
import viteImagemin from 'vite-plugin-imagemin'

const fs = require('fs')

export default defineConfig({
    plugins: [
        liveReload([
            'themes/opatrimoine/**/*.php',
            'resources/**'
        ],{ alwaysReload: true }),
        splitVendorChunkPlugin(),
        viteImagemin({
            gifsicle: {
                optimizationLevel: 7,
                interlaced: false,
            },
            optipng: {
                optimizationLevel: 7,
            },
            mozjpeg: {
                quality: 80,
            },
            pngquant: {
                quality: [0.8, 0.9],
                speed: 4,
            },
            svgo: {
                plugins: [
                    {
                        name: 'removeViewBox',
                    },
                    {
                        name: 'removeEmptyAttrs',
                        active: false,
                    },
                ],
            },
        }),
    ],
    server: {
        // required to load scripts from custom host
        // url for hot-refresh page : https://127.0.0.1/opatrimoine/ or https://localhost/opatrimoine/
        // url http://opatrimoine.test/ will not be refreshed
        // ? sometimes need to re-open browser, because of local SSL need to refresh app.js resources via browser
        cors: true,
        //Certificats local. Générer un certificat : https://github.com/FiloSottile/mkcert
        //If it doesn't work you can set it to : true for invalid cert
        //or test with this : https://vitejs.dev/guide/migration.html#automatic-https-certificate-generation
        https: {
            key: fs.readFileSync('opatrimoine-key.pem'),
            cert: fs.readFileSync('opatrimoine.pem')
        },
        strictPort: true,
        port: 5173,
        // used to load resources (cf themes/opatrimoine/functions.php)
        // Hot Module Replacement : https://vitejs.dev/config/server-options.html#server-hmr
        hmr: {
            host: 'localhost'
        }
    },
    base: '/',
    publicDir: 'resources/static',
    build: {
        outDir: './themes/opatrimoine/assets',
        assetsDir: '',
        manifest: true,
        emptyOutDir: true,
        target: 'es2015',
        rollupOptions: {
            // overwrite default .html entry
            input: {
                app:'resources/js/app.js',
                // admin:'resources/js/admin.js'
            }
        }
    },
})