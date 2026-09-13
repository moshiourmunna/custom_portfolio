import { Link } from '@inertiajs/react';
import { Icon, IconBtn } from '../icons';

function day(value) {
  if (!value) return '';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value).slice(0, 10);
  return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}

const stats = [
  ['products', 'Products', 'Catalog', 'box'],
  ['inquiries', 'Inquiries', 'Inbox', 'chat'],
  ['news', 'News', 'Published', 'paper'],
  ['gallery', 'Gallery items', 'Mill photographs', 'photo'],
];

export default function Dashboard({ counts, inquiries, posts, jobs }) {
  const today = new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
  return (
    <>
      <header className="admin-top dash-top">
        <div>
          <h1>Dashboard</h1>
          <p className="dash-lead">Overview of the mill content library.</p>
        </div>
        <div className="admin-top__actions">
          <span className="dash-today">
            <Icon name="calendar" />
            <span>{today}</span>
          </span>
          <a className="btn btn-outline btn--labeled" href="/" target="_blank" rel="noreferrer">View site</a>
        </div>
      </header>
      <div className="admin-content">
        <div className="dash">
          <div className="dash-stats">
            {stats.map(([key, label, note, icon]) => (
              <Link className="dash-stat" href={`/admin/${key === 'news' ? 'news' : key}`} key={key}>
                <span className="dash-stat__icon" aria-hidden="true"><Icon name={icon} /></span>
                <span className="dash-stat__body">
                  <span className="dash-stat__label">{label}</span>
                  <strong>{counts[key] ?? 0}</strong>
                  <span className="dash-stat__note">{note}</span>
                </span>
                <span className="dash-stat__go" aria-hidden="true"><Icon name="chevron" /></span>
              </Link>
            ))}
          </div>
          <section className="dash-panel" aria-label="Recent inquiries">
            <div className="dash-panel__head">
              <h2>Recent inquiries</h2>
              <Link className="btn btn-primary dash-view" href="/admin/inquiries">View all inquiries</Link>
            </div>
            <div className="dash-table-wrap">
              <table className="dash-table">
                <thead>
                  <tr><th>Company</th><th>Interest</th><th>Message</th><th>Date</th><th>Status</th><th><span className="visually-hidden">Actions</span></th></tr>
                </thead>
                <tbody>
                  {inquiries.map((row) => (
                    <tr key={row.id}>
                      <td>{row.company || row.name || '—'}</td>
                      <td>{row.interest}</td>
                      <td>{row.message}</td>
                      <td>{day(row.received_on)}</td>
                      <td><span className={`dash-status${/progress/i.test(row.status || '') ? ' is-progress' : /closed/i.test(row.status || '') ? ' is-closed' : ''}`}>{/progress/i.test(row.status || '') ? 'In progress' : /closed/i.test(row.status || '') ? 'Closed' : 'New'}</span></td>
                      <td><IconBtn kind="view" label="Open inbox" href="/admin/inquiries" /></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </section>
          <div className="dash-split">
            <section className="dash-panel" aria-label="Latest news">
              <div className="dash-panel__head"><h2>Latest news</h2><Link href="/admin/news">View all</Link></div>
              <div className="dash-feed">
                {posts.map((post) => <p key={post.id}><Link href={`/admin/news/${post.id}/edit`}>{post.title}</Link></p>)}
              </div>
            </section>
            <section className="dash-panel" aria-label="Open roles">
              <div className="dash-panel__head"><h2>Open roles</h2><Link href="/admin/careers">View all</Link></div>
              <div className="dash-feed">
                {jobs.map((job) => <p key={job.id}>{job.title} · {job.location}</p>)}
              </div>
            </section>
          </div>
        </div>
      </div>
    </>
  );
}