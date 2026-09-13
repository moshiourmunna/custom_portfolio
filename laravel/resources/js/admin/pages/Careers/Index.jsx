import { Link, router } from '@inertiajs/react';

export default function Index({ jobs }) {
  return (
    <>
      <header className="admin-top"><h1>Careers</h1><Link className="btn btn-primary" href="/admin/careers/create">Add role</Link></header>
      <div className="admin-content">
        <table className="dash-table">
          <tbody>
            {jobs.map((job) => (
              <tr key={job.id}>
                <td>{job.title}</td>
                <td>{job.location}</td>
                <td>{job.status}</td>
                <td>{job.applications_count} applications</td>
                <td><Link href={`/admin/careers/${job.id}/edit`}>Edit</Link> <button type="button" onClick={() => router.delete(`/admin/careers/${job.id}`)}>Delete</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}
