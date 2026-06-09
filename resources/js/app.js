import { registerSW } from 'virtual:pwa-register';

if ('serviceWorker' in navigator) {
    registerSW({
        immediate: true,
        onOfflineReady() {
            console.info('PWA pronto para uso offline (assets em cache).');
        },
    });
}
