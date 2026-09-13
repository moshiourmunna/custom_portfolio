import { Link, router } from '@inertiajs/react';
import { Icon, IconBtn } from '../../icons';
import { Actions, Catalog, Status, Thumb } from '../../ui';

export default function Index({ products }) {
  return (
    <Catalog
      title="Products"
      viewHref="/products/woven-fabric"
      action={<Link className="btn btn-primary btn--labeled btn--with-icon catalog-add" href="/admin/products/create"><Icon name="plus" /> Add product</Link>}
      columns={['Image', 'Title', 'Category', 'Status', 'Updated', 'Actions']}
      searchKeys={['title', 'category', 'status']}
      facets={[{ key: 'category', label: 'Select category', values: [...new Set(products.map((product) => product.category?.name).filter(Boolean))] }]}
      rows={products.map((product) => ({
        id: product.id,
        title: product.title,
        category: product.category?.name,
        status: product.status,
        cells: [
          <td key="image"><Thumb path={product.image} alt={product.title} /></td>,
          <td key="title"><span className="catalog-title"><strong>{product.title}</strong></span></td>,
          <td key="category">{product.category?.name ? <span className="catalog-cat">{product.category.name}</span> : null}</td>,
          <td key="status"><Status value={product.status} /></td>,
          <td key="updated">{product.updated_at ? String(product.updated_at).slice(0, 10) : ''}</td>,
          <td key="actions">
            <Actions>
              <IconBtn kind="view" label="View on site" href={`/products/${product.slug}`} external />
              <IconBtn kind="edit" label="Edit" href={`/admin/products/${product.id}/edit`} />
              <IconBtn kind="trash" label="Delete" danger onClick={() => router.delete(`/admin/products/${product.id}`)} />
            </Actions>
          </td>,
        ],
      }))}
    />
  );
}