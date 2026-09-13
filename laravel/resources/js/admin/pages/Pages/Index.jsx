import { Link } from '@inertiajs/react';
import { IconBtn } from '../../icons';
import { Actions, Catalog, Status } from '../../ui';

const related = [
  ['/admin/home', 'Home editor'],
  ['about', 'About'],
  ['process', 'Process'],
  ['facilities', 'Facilities'],
  ['quality', 'Quality'],
  ['sustainability', 'Sustainability'],
  ['contact', 'Contact'],
  ['privacy', 'Legal'],
];

export default function Index({ pages }) {
  const pageBySlug = Object.fromEntries(pages.map((page) => [page.slug, page]));
  return (
    <Catalog
      title="Pages"
      viewHref="/"
      note="These public routes are fixed. Saving a page editor updates the live site copy."
      columns={['Page', 'Path', 'Status', 'Actions']}
      searchKeys={['title', 'slug', 'status']}
      rows={pages.map((page) => ({
        id: page.id,
        title: page.title,
        slug: page.slug,
        status: page.status,
        cells: [
          <td key="title">{page.title}</td>,
          <td key="path">/{page.slug === 'success' ? 'quote-success' : page.slug}</td>,
          <td key="status"><Status value={page.status} /></td>,
          <td key="actions">
            <Actions>
              <IconBtn kind="view" label="View on site" href={page.slug === 'success' ? '/quote-success' : `/${page.slug}`} external />
              <IconBtn kind="edit" label="Edit" href={`/admin/pages/${page.id}`} />
            </Actions>
          </td>,
        ],
      }))}
    >
      <nav className="related-links" aria-label="Related editors">
        {related.map(([slug, label]) => {
          const href = slug.startsWith('/') ? slug : pageBySlug[slug] ? `/admin/pages/${pageBySlug[slug].id}` : '/admin/pages';
          return <Link key={label} href={href}>{label}</Link>;
        })}
      </nav>
    </Catalog>
  );
}
