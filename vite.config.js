import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: false,
            filename: 'sw.js',
            manifestFilename: 'manifest.webmanifest',
            includeAssets: ['favicon.ico', 'icons/icon-192.png', 'icons/icon-512.png'],
            manifest: {
                name: 'Adote um Estudante',
                short_name: 'Adote Aluno',
                description: 'Ajude estudantes com mensalidades escolares. PIX direto para a instituição.',
                theme_color: '#0d9488',
                background_color: '#f0fdfa',
                display: 'standalone',
                start_url: '/',
                scope: '/',
                lang: 'pt-BR',
                orientation: 'portrait-primary',
                icons: [
                    {
                        src: '/icons/icon-192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any',
                    },
                    {
                        src: '/icons/icon-512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any',
                    },
                    {
                        src: '/icons/icon-512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable',
                    },
                ],
            },
            workbox: {
                cleanupOutdatedCaches: true,
                clientsClaim: true,
                skipWaiting: true,
                navigateFallback: null,
                globPatterns: ['**/*.{js,css,woff2,woff,ico}'],
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/fonts\.bunny\.net\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'bunny-fonts',
                            expiration: {
                                maxEntries: 10,
                                maxAgeSeconds: 60 * 60 * 24 * 365,
                            },
                        },
                    },
                    {
                        urlPattern: /\/icons\/.*\.png$/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'app-icons',
                            expiration: { maxEntries: 4 },
                        },
                    },
                    {
                        urlPattern: /\/storage\/.*/i,
                        handler: 'NetworkOnly',
                    },
                    {
                        urlPattern: /\/livewire\/.*/i,
                        handler: 'NetworkOnly',
                    },
                ],
            },
            devOptions: {
                enabled: false,
            },
        }),
    ],
});
