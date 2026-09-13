import { Link, router } from '@inertiajs/react';

export default function Index({ products }) {
  return (
    <>
      <header className="admin-top">
        <h1>Products</h1>
        <Link className="btn btn-primary" href="/admin/products/create">Add product</Link>
      </header>
      <div className="admin-content">
        <table className="dash-table">
          <thead><tr><th>Title</th><th>Category</th><th>Status</th><th></th></tr></thead>
          <tbody>
            {products.map((product) => (
              <tr key={product.id}>
                <td>{product.title}</td>
                <td>{product.category?.name}</td>
                <td>{product.status}</td>
                <td>
                  <Link href={`/admin/products/${product.id}/edit`}>Edit</Link>
                  {' '}
                  <button type="button" onClick={() => router.delete(`/admin/products/${product.id}`)}>Delete</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}
