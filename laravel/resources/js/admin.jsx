import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import Layout from './admin/Layout';

const pages = import.meta.glob('./admin/pages/**/*.jsx', { eager: true });

createInertiaApp({
  title: (title) => (title ? `${title} | Islam Textile Admin` : 'Islam Textile Admin'),
  resolve: (name) => {
    const page = pages[`./admin/pages/${name}.jsx`];
    if (!page) throw new Error(`Missing admin page ${name}`);
    page.default.layout = page.default.layout || ((content) => <Layout>{content}</Layout>);
    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
});
