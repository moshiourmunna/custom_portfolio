import { Link, router } from '@inertiajs/react';
import { Icon, IconBtn } from '../../icons';
import { Actions, Catalog, Status } from '../../ui';

export default function Index({ posts }) {
  return (
    <Catalog
      title="News"
      viewHref="/news"
      action={<Link className="btn btn-primary btn--labeled btn--with-icon catalog-add" href="/admin/news/create"><Icon name="plus" /> Add post</Link>}
      columns={['Title', 'Status', 'Date', 'Actions']}
      searchKeys={['title', 'status']}
      rows={posts.map((post) => ({
        id: post.id,
        title: post.title,
        status: post.status,
        cells: [
          <td key="title">{post.title}</td>,
          <td key="status"><Status value={post.status} /></td>,
          <td key="date">{post.published_on}</td>,
          <td key="actions">
            <Actions>
              <IconBtn kind="view" label="View on site" href={`/news/${post.slug}`} external />
              <IconBtn kind="edit" label="Edit" href={`/admin/news/${post.id}/edit`} />
              <IconBtn kind="trash" label="Delete" danger onClick={() => router.delete(`/admin/news/${post.id}`)} />
            </Actions>
          </td>,
        ],
      }))}
    />
  );
}