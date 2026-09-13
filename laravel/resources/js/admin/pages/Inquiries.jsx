import { Link, router } from '@inertiajs/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import { Icon, IconBtn } from '../icons';
import { Actions, Thumb } from '../ui';

const STATUSES = ['New', 'In progress', 'Closed'];

function statusLabel(value) {
  const key = String(value || '').trim().toLowerCase().replace(/[\s-]+/g, '_');
  if (key === 'in_progress') return 'In progress';
  if (key === 'closed') return 'Closed';
  return 'New';
}

function dashDate(value) {
  if (!value) return '—';
  const date = new Date(`${String(value).slice(0, 10)}T00:00:00`);
  if (Number.isNaN(date.getTime())) return '—';
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

function excerpt(value) {
  const text = String(value || '').replace(/\s+/g, ' ').trim();
  if (text.length <= 88) return text;
  return `${text.slice(0, 87).trim()}…`;
}

function inquiryImage(row) {
  if (row.image) return row.image;
  const interest = String(row.interest || '').toLowerCase();
  if (interest.includes('yarn')) return 'media/gallery/gallery-7.jpg';
  if (interest.includes('finish')) return 'media/products/product-3.jpg';
  return 'media/products/product-1.jpg';
}

export default function Inquiries({ inquiries, links = {} }) {
  const rows = inquiries.map((row) => ({ ...row, label: statusLabel(row.status) }));
  const [query, setQuery] = useState('');
  const [status, setStatus] = useState('');
  const [pageSize, setPageSize] = useState(10);
  const [page, setPage] = useState(1);
  const [openId, setOpenId] = useState(null);
  const [note, setNote] = useState('');
  const noteRef = useRef(null);
  const open = rows.find((row) => row.id === openId) || null;
  const counts = {
    All: rows.length,
    New: rows.filter((row) => row.label === 'New').length,
    'In progress': rows.filter((row) => row.label === 'In progress').length,
    Closed: rows.filter((row) => row.label === 'Closed').length,
  };
  const filtered = useMemo(() => rows.filter((row) => {
    const hay = `${row.company || ''} ${row.name || ''} ${row.interest || ''} ${row.message || ''}`.toLowerCase();
    return (!query || hay.includes(query.toLowerCase())) && (!status || row.label === status);
  }), [rows, query, status]);
  const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
  const current = Math.min(page, pages);
  const start = (current - 1) * pageSize;
  const visible = filtered.slice(start, start + pageSize);
  const contactHref = links.contact ? `/admin/pages/${links.contact}` : '/admin/pages';
  const privacyHref = links.privacy ? `/admin/pages/${links.privacy}` : '/admin/pages';

  function jump(next) {
    setStatus(next);
    setPage(1);
  }

  function show(row, edit) {
    setNote(row.note || '');
    setOpenId(row.id);
    if (edit) window.setTimeout(() => noteRef.current?.focus(), 0);
  }

  useEffect(() => {
    if (openId && !rows.some((row) => row.id === openId)) setOpenId(null);
  }, [openId, rows]);

  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>Inquiries</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span>Inquiries</p>
        </div>
        <div className="admin-top__actions">
          <a className="btn btn-outline btn--labeled" href="/contact" target="_blank" rel="noreferrer">View contact</a>
        </div>
      </header>
      <div className="admin-content catalog-page">
        <div className="list-stats">
          {[['All', ''], ['New', 'New'], ['In progress', 'In progress'], ['Closed', 'Closed']].map(([label, value]) => (
            <button key={label} type="button" className={`list-chip${status === value ? ' is-active' : ''}`} onClick={() => jump(value)}>
              {label} <strong>{counts[label]}</strong>
            </button>
          ))}
        </div>
        <nav className="related-links" aria-label="Related editors">
          <Link href={contactHref}>Contact copy</Link>
          <Link href="/admin/settings">Contact details</Link>
          <Link href={privacyHref}>Privacy</Link>
        </nav>
        <section className="catalog-card" aria-label="Inquiry inbox">
          <div className="catalog-filters">
            <label className="catalog-search">
              <input type="search" value={query} onChange={(event) => { setQuery(event.target.value); setPage(1); }} placeholder="Search company, interest, or message…" aria-label="Search inquiries" />
              <Icon name="search" />
            </label>
            <select value={status} aria-label="Status" onChange={(event) => jump(event.target.value)}>
              <option value="">Select status</option>
              {STATUSES.map((item) => <option key={item}>{item}</option>)}
            </select>
            <button type="button" className="btn btn-outline btn--labeled catalog-reset" onClick={() => { setQuery(''); jump(''); }}>Reset</button>
            <button type="button" className="btn btn-primary btn--labeled catalog-filter"><Icon name="filter" /> Filter</button>
          </div>
          <p className="catalog-note">Status changes save to the inbox. The public contact form is not rewritten.</p>
          <div className="catalog-table-wrap">
            <table className="catalog-table">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Company</th>
                  <th>Interest</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {visible.map((row) => (
                  <tr key={row.id}>
                    <td data-label="Image"><Thumb path={inquiryImage(row)} alt={row.interest} /></td>
                    <td data-label="Company">
                      <span className="catalog-title">
                        <strong>{row.company || row.name || 'Inquiry'}</strong>
                        {excerpt(row.message) ? <small>{excerpt(row.message)}</small> : null}
                      </span>
                    </td>
                    <td data-label="Interest">{row.interest ? <span className="catalog-cat">{row.interest}</span> : '—'}</td>
                    <td data-label="Date">{dashDate(row.received_on)}</td>
                    <td data-label="Status">
                      <select
                        className="catalog-select"
                        value={row.label}
                        aria-label={`Status for ${row.company || 'inquiry'}`}
                        onChange={(event) => router.put(`/admin/inquiries/${row.id}`, { status: event.target.value }, { preserveScroll: true })}
                      >
                        {STATUSES.map((item) => <option key={item}>{item}</option>)}
                      </select>
                    </td>
                    <td data-label="Actions">
                      <Actions>
                        <IconBtn kind="view" label="View inquiry" onClick={() => show(row, false)} />
                        <IconBtn kind="edit" label="Edit note" onClick={() => show(row, true)} />
                        <IconBtn kind="trash" label="Delete" danger onClick={() => {
                          if (window.confirm('Delete this inquiry? The public contact form is not rewritten.')) {
                            router.delete(`/admin/inquiries/${row.id}`, { preserveScroll: true });
                          }
                        }} />
                      </Actions>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <div className="catalog-foot">
            <p>Showing {filtered.length ? start + 1 : 0}–{Math.min(start + pageSize, filtered.length)} of {filtered.length} results</p>
            <div className="catalog-pager">
              <label>Rows
                <select value={pageSize} aria-label="Rows per page" onChange={(event) => { setPageSize(Number(event.target.value)); setPage(1); }}>
                  {[5, 10, 25].map((size) => <option key={size}>{size}</option>)}
                </select>
              </label>
              <div>
                <button type="button" className="btn btn-outline btn--labeled" disabled={current <= 1} onClick={() => setPage(current - 1)}>Prev</button>
                <button type="button" className="btn btn-outline btn--labeled" disabled={current >= pages} onClick={() => setPage(current + 1)}>Next</button>
              </div>
            </div>
          </div>
        </section>
        {open ? (
          <div className="modal-back" onClick={() => setOpenId(null)}>
            <div className="modal" role="dialog" aria-modal="true" aria-labelledby="inquiry-title" onClick={(event) => event.stopPropagation()}>
              <div className="dash-panel__head">
                <h2 id="inquiry-title">{open.company || open.name || 'Inquiry'}</h2>
                <IconBtn kind="close" label="Close" onClick={() => setOpenId(null)} />
              </div>
              <dl className="inquiry-facts">
                {[['Name', open.name], ['Email', open.email], ['Phone', open.phone], ['Country', open.country], ['Interest', open.interest], ['Date', dashDate(open.received_on)]].filter(([, value]) => value).map(([label, value]) => (
                  <div key={label}><dt>{label}</dt><dd>{value}</dd></div>
                ))}
              </dl>
              {open.message ? <p>{open.message}</p> : null}
              {open.attachment_path ? <p><a className="btn btn-outline btn--labeled" href={`/admin/inquiries/${open.id}/attachment`}>Download attachment</a></p> : null}
              {open.email ? <p><a className="btn btn-outline btn--labeled" href={`mailto:${open.email}`}>Reply by email</a></p> : null}
              <form onSubmit={(event) => { event.preventDefault(); router.put(`/admin/inquiries/${open.id}`, { status: open.label, note }, { preserveScroll: true }); }}>
                <div className="field">
                  <label htmlFor="inquiry-note">Internal note</label>
                  <textarea id="inquiry-note" ref={noteRef} rows={4} value={note} onChange={(event) => setNote(event.target.value)} />
                  <span className="hide-note">Not shown on the public site</span>
                </div>
                <div className="save-bar">
                  <button className="btn btn-primary btn--labeled btn--with-icon" type="submit"><Icon name="check" /> Save note</button>
                  <button className="btn btn-outline btn--labeled" type="button" onClick={() => setOpenId(null)}>Close</button>
                </div>
              </form>
            </div>
          </div>
        ) : null}
      </div>
    </>
  );
}
