import { Link } from '@inertiajs/react';

export default function Dashboard({ counts, inquiries, posts, jobs }) {
  return (
    <>
      <header className="admin-top dash-top">
        <div>
          <h1>Dashboard</h1>
          <p className="dash-lead">Overview of the mill content library.</p>
        </div>
        <a className="btn btn-outline" href="/" target="_blank" rel="noreferrer">View site</a>
      </header>
      <div className="admin-content">
        <div className="dash-stats">
          {[
            ['Products', counts.products, '/admin/products'],
            ['Inquiries', counts.inquiries, '/admin/inquiries'],
            ['News', counts.news, '/admin/news'],
            ['Gallery items', counts.gallery, '/admin/gallery'],
          ].map(([label, count, href]) => (
            <Link className="dash-stat" href={href} key={label}>
              <span className="dash-stat__body"><span className="dash-stat__label">{label}</span><strong>{count}</strong></span>
            </Link>
          ))}
        </div>
        <section className="dash-panel">
          <div className="dash-panel__head"><h2>Recent inquiries</h2><Link href="/admin/inquiries">View all</Link></div>
          <table className="dash-table">
            <thead><tr><th>Company</th><th>Interest</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
              {inquiries.map((row) => (
                <tr key={row.id}><td>{row.company || row.name || '—'}</td><td>{row.interest}</td><td>{row.status}</td><td>{row.received_on}</td></tr>
              ))}
            </tbody>
          </table>
        </section>
        <div className="dash-split">
          <section className="dash-panel">
            <h2>Latest news</h2>
            {posts.map((post) => <p key={post.id}><Link href={`/admin/news/${post.id}/edit`}>{post.title}</Link></p>)}
          </section>
          <section className="dash-panel">
            <h2>Open roles</h2>
            {jobs.map((job) => <p key={job.id}>{job.title} · {job.location}</p>)}
          </section>
        </div>
      </div>
    </>
  );
}
