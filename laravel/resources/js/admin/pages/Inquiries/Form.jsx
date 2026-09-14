import { Link, useForm } from '@inertiajs/react';
import { Icon } from '../../icons';
import { UploadField } from '../../ui';

const INTERESTS = ['Yarn / Spinning', 'Woven Fabric (Greige)', 'Finished / Piece-dyed Fabric', 'Custom program'];
const empty = { name: '', company: '', email: '', phone: '', country: '', interest: '', message: '', status: 'New', note: '', image: '', received_on: new Date().toISOString().slice(0, 10), attachment: null };

export default function Form() {
  const form = useForm(empty);
  const save = () => {
    form.transform((data) => {
      const next = { ...data };
      if (!next.attachment) delete next.attachment;
      return next;
    });
    form.post('/admin/inquiries', { forceFormData: true });
  };

  return (
    <>
      <header className="admin-top product-top">
        <div>
          <h1>Add inquiry</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span><Link href="/admin/inquiries">Inquiries</Link><span aria-hidden="true">/</span>Add</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={save} disabled={form.processing}><Icon name="check" /> Save</button>
      </header>
      <div className="admin-content product-form-page">
        <form className="product-layout" onSubmit={(event) => { event.preventDefault(); save(); }}>
          <div className="product-main">
            <section className="product-card">
              <div className="form-grid two">
                <div className="field"><label>Company</label><input value={form.data.company} onChange={(event) => form.setData('company', event.target.value)} />{form.errors.company ? <span className="file-field__error">{form.errors.company}</span> : null}</div>
                <div className="field"><label>Name</label><input value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} /></div>
                <div className="field"><label>Email</label><input type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} />{form.errors.email ? <span className="file-field__error">{form.errors.email}</span> : null}</div>
                <div className="field"><label>Phone</label><input value={form.data.phone} onChange={(event) => form.setData('phone', event.target.value)} /></div>
                <div className="field"><label>Country</label><input value={form.data.country} onChange={(event) => form.setData('country', event.target.value)} /></div>
                <div className="field">
                  <label>Interest</label>
                  <select value={form.data.interest} onChange={(event) => form.setData('interest', event.target.value)}>
                    <option value="">Select…</option>
                    {INTERESTS.map((item) => <option key={item}>{item}</option>)}
                  </select>
                </div>
                <div className="field"><label>Date</label><input type="date" value={form.data.received_on} onChange={(event) => form.setData('received_on', event.target.value)} /></div>
                <div className="field">
                  <label>Status</label>
                  <select value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}>
                    {['New', 'In progress', 'Closed'].map((item) => <option key={item}>{item}</option>)}
                  </select>
                </div>
              </div>
              <div className="field"><label>Message</label><textarea rows={5} value={form.data.message} onChange={(event) => form.setData('message', event.target.value)} /></div>
              <div className="field"><label>Internal note</label><textarea rows={3} value={form.data.note} onChange={(event) => form.setData('note', event.target.value)} /><span className="hide-note">Not shown on the public site</span></div>
            </section>
          </div>
          <aside className="product-side">
            <section className="product-card">
              <h2>Inbox image</h2>
              <UploadField variant="drop" label="Inbox image" value={form.data.image} onChange={(value) => form.setData('image', value)} hint="Shown on the inquiry row. JPG, PNG, WEBP, or GIF." />
            </section>
            <section className="product-card">
              <h2>Attachment</h2>
              <div className="field">
                <label>Spec or document</label>
                <input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" onChange={(event) => form.setData('attachment', event.target.files?.[0] || null)} />
                <span className="hint">Stored privately. PDF, Word, Excel, or image. Max 20MB.</span>
                {form.errors.attachment ? <span className="file-field__error">{form.errors.attachment}</span> : null}
              </div>
            </section>
          </aside>
          <div className="product-actions">
            <Link className="btn btn-outline btn--labeled" href="/admin/inquiries">Cancel</Link>
            <button className="btn btn-primary btn--labeled btn--with-icon" type="submit" disabled={form.processing}><Icon name="check" /> Save inquiry</button>
          </div>
        </form>
      </div>
    </>
  );
}
