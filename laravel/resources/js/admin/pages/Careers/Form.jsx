import { Link, useForm } from '@inertiajs/react';
import { Icon } from '../../icons';
import { mediaUrl } from '../../ui';

const empty = { title: '', slug: '', location: '', department: '', employment_type: 'Full-time', summary: '', description: '', image: '', status: 'open' };

export default function Form({ job }) {
  const form = useForm({ ...empty, ...job });
  const save = () => job ? form.put(`/admin/careers/${job.id}`) : form.post('/admin/careers');
  return (
    <>
      <header className="admin-top product-top">
        <div>
          <h1>{job ? job.title : 'Add role'}</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span><Link href="/admin/careers">Careers</Link><span aria-hidden="true">/</span>{job ? 'Edit' : 'Add'}</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={save}><Icon name="check" /> Save</button>
      </header>
      <div className="admin-content product-form-page">
        <form className="product-layout" onSubmit={(event) => { event.preventDefault(); save(); }}>
          <div className="product-main">
            <section className="product-card">
              <div className="form-grid two">
                <div className="field"><label>Title</label><input value={form.data.title} onChange={(event) => form.setData('title', event.target.value)} required /></div>
                <div className="field"><label>Slug</label><input value={form.data.slug || ''} onChange={(event) => form.setData('slug', event.target.value)} /></div>
                <div className="field"><label>Department</label><input value={form.data.department || ''} onChange={(event) => form.setData('department', event.target.value)} /></div>
                <div className="field"><label>Location</label><input value={form.data.location || ''} onChange={(event) => form.setData('location', event.target.value)} /></div>
                <div className="field"><label>Type</label><input value={form.data.employment_type || ''} onChange={(event) => form.setData('employment_type', event.target.value)} /></div>
              </div>
              <div className="field"><label>Summary</label><textarea rows={3} value={form.data.summary || ''} onChange={(event) => form.setData('summary', event.target.value)} /><span className="hide-note">Hidden on the site when empty</span></div>
              <div className="field"><label>Description</label><textarea rows={8} value={form.data.description || ''} onChange={(event) => form.setData('description', event.target.value)} /></div>
            </section>
            {job?.applications?.length ? (
              <section className="product-card">
                <h2>Applications</h2>
                {job.applications.map((application) => <p key={application.id}>{application.name} · {application.email} · CV stored privately</p>)}
              </section>
            ) : null}
          </div>
          <aside className="product-side">
            <section className="product-card">
              <h2>Role image</h2>
              <div className="product-drop">
                {form.data.image ? <img alt="" src={mediaUrl(form.data.image)} /> : null}
                <input value={form.data.image || ''} onChange={(event) => form.setData('image', event.target.value)} placeholder="media/…" />
                <span className="product-drop__hit"><Icon name="upload" /><strong>Image path</strong><span>Optional mill photograph</span></span>
              </div>
            </section>
            <section className="product-card">
              <h2>Publish</h2>
              <div className="field">
                <label>Status</label>
                <div className="status-field"><i aria-hidden="true" /><select value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}><option value="open">Open</option><option value="closed">Closed</option></select></div>
              </div>
            </section>
          </aside>
        </form>
      </div>
    </>
  );
}