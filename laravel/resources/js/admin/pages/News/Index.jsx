import { Link, router } from '@inertiajs/react';

export default function Index({ posts }) {
  return (
    <>
      <header className="admin-top"><h1>News</h1><Link className="btn btn-primary" href="/admin/news/create">Add post</Link></header>
      <div className="admin-content">
        <table className="dash-table">
          <tbody>
            {posts.map((post) => (
              <tr key={post.id}>
                <td>{post.title}</td>
                <td>{post.status}</td>
                <td>{post.published_on}</td>
                <td><Link href={`/admin/news/${post.id}/edit`}>Edit</Link> <button type="button" onClick={() => router.delete(`/admin/news/${post.id}`)}>Delete</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}
