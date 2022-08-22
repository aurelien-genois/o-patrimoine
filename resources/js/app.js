/* https://vitejs.dev/guide/backend-integration.html */
import 'vite/modulepreload-polyfill';

/* CSS FILES */
import '../scss/app.scss';

function alternateColor() {
    const siteTitle = document.querySelector('.site-title');
    if(siteTitle) {
        (siteTitle.classList.contains('mt-12')) ? siteTitle.classList.remove('mt-12') : siteTitle.classList.add('mt-12');
    }
}

const interval = setInterval(alternateColor, 1000);
setTimeout(() => {
    clearInterval(interval);
}, 10000);
