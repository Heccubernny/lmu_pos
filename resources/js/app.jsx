import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import '../css/app.css';
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
createInertiaApp({
    title: (title) => title ? `${title} — T-Conn POS` : 'T-Conn POS',
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});