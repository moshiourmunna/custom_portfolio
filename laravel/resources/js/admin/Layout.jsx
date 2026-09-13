import { Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

const groups = [
  { label: 'Overview', items: [['dashboard', '/admin', 'Dashboard'], ['home', '/admin/home', 'Home']] },
  { label: 'Content', items: [['pages', '/admin/pages', 'Pages'], ['products', '/admin/products', 'Products'], ['categories', '/admin/categories', 'Categories'], ['gallery', '/admin/gallery', 'Gallery'], ['news', '/admin/news', 'News'], ['careers', '/admin/careers', 'Careers']] },
  { label: 'Library', items: [['inquiries', '/admin/inquiries', 'Inquiries'], ['media', '/admin/media', 'Media']] },
  { label: 'System', items: [['settings', '/admin/settings', 'Settings']] },
];

export default function Layout({ children }) {
  const { auth, flash } = usePage().props;
  const [open, setOpen] = useState(false);
  const path = typeof window !== 'undefined' ? window.location.pathname : '';
  const active = path.startsWith('/admin/pages') ? 'pages'
    : path.startsWith('/admin/products') ? 'products'
    : path.startsWith('/admin/news') ? 'news'
    : path.startsWith('/admin/careers') ? 'careers'
    : path.replace('/admin/', '').split('/')[0] || 'dashboard';

  return (
    <>
      <div className="admin-backdrop" hidden={!open} onClick={() => setOpen(false)} />
      <div className="admin-shell">
        <header className="admin-nav">
          <button className="nav-toggle" type="button" aria-label="Open menu" onClick={() => setOpen((value) => !value)}><span /></button>
          <Link className="admin-nav__brand" href="/admin">
            <span className="admin-nav__mark" aria-hidden="true" />
            <span className="admin-nav__word"><strong>Islam Textile</strong><small>Weaving Tradition, Ensuring Quality</small></span>
          </Link>
          <div className="admin-nav__tools">
            <Link className="admin-nav__bell" href="/admin/inquiries" aria-label="Inquiries">Inbox</Link>
            <div className="admin-nav__user">
              <span className="admin-nav__user-text"><strong>{auth.user?.name}</strong><small>{auth.user?.role}</small></span>
            </div>
          </div>
        </header>
        <aside className={`sidebar${open ? ' is-open' : ''}`} id="admin-sidebar">
          <nav className="sidebar__nav" aria-label="Admin">
            {groups.map((group) => (
              <div key={group.label}>
                <p className="sidebar__label">{group.label}</p>
                {group.items.filter((item) => item[0] !== 'settings' || auth.user?.role === 'super-admin').map(([id, href, label]) => (
                  <Link key={id} className={`sidebar__link${active === id || (id === 'dashboard' && path === '/admin') ? ' is-active' : ''}`} href={href}>
                    <span>{label}</span>
                  </Link>
                ))}
              </div>
            ))}
          </nav>
          <div className="sidebar__foot">
            <p className="sidebar__mill"><strong>Islam Textile</strong><span>Dhaka · Narayanganj</span></p>
            <button className="sidebar__link sidebar__link--logout" type="button" onClick={() => router.post('/admin/logout')}>Logout</button>
          </div>
        </aside>
        <div className="admin-main">
          {flash?.status ? <p className="catalog-note">{flash.status}</p> : null}
          {children}
        </div>
      </div>
    </>
  );
}
