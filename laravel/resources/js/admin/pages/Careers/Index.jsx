import { Link, router } from '@inertiajs/react';
import { Icon, IconBtn } from '../../icons';
import { Actions, Catalog, Status } from '../../ui';

export default function Index({ jobs }) {
  return (
    <Catalog
      title="Careers"
      viewHref="/careers"
      action={<Link className="btn btn-primary btn--labeled btn--with-icon catalog-add" href="/admin/careers/create"><Icon name="plus" /> Add role</Link>}
      columns={['Role', 'Location', 'Status', 'Applications', 'Actions']}
      searchKeys={['title', 'location', 'status']}
      rows={jobs.map((job) => ({
        id: job.id,
        title: job.title,
        location: job.location,
        status: job.status,
        cells: [
          <td key="title">{job.title}</td>,
          <td key="location">{job.location}</td>,
          <td key="status"><Status value={job.status} /></td>,
          <td key="apps">{job.applications_count}</td>,
          <td key="actions">
            <Actions>
              <IconBtn kind="view" label="View on site" href={`/careers/${job.slug}`} external />
              <IconBtn kind="edit" label="Edit" href={`/admin/careers/${job.id}/edit`} />
              <IconBtn kind="trash" label="Delete" danger onClick={() => router.delete(`/admin/careers/${job.id}`)} />
            </Actions>
          </td>,
        ],
      }))}
    />
  );
}