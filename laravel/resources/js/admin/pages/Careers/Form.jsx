import { useForm } from '@inertiajs/react';

const empty = { title: '', slug: '', location: '', department: '', employment_type: 'Full-time', summary: '', description: '', image: '', status: 'open' };

export default function Form({ job }) {
  const form = useForm({ ...empty, ...job });
  return (
    <>
      <header className="admin-top">
        <h1>{job ? 'Edit role' : 'New role'}</h1>
        <button className="btn btn-primary" type="button" onClick={() => job ? form.put(`/admin/careers/${job.id}`) : form.post('/admin/careers')}>Save</button>
      </header>
      <div className="admin-content">
        {Object.keys(empty).map((key) => (
          <div className="field" key={key}>
            <label>{key}</label>
            <input value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />
          </div>
        ))}
        {job?.applications?.map((application) => (
          <p key={application.id}>{application.name} · {application.email} · CV stored privately</p>
        ))}
      </div>
    </>
  );
}
