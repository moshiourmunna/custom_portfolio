import { Link } from '@inertiajs/react';

export default function Index({ pages }) {
  return (
    <>
      <header className="admin-top"><h1>Pages</h1></header>
      <div className="admin-content">
        <table className="dash-table">
          <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th></th></tr></thead>
          <tbody>
            {pages.map((page) => (
              <tr key={page.id}>
                <td>{page.title}</td>
                <td>{page.slug}</td>
                <td>{page.status}</td>
                <td><Link href={`/admin/pages/${page.id}`}>Edit</Link></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}
