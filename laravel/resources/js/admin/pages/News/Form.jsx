import { Link, useForm } from '@inertiajs/react';
import { Icon } from '../../icons';
import { UploadField } from '../../ui';

const empty = { title: '', slug: '', lead: '', body: '', cover: '', category: '', published_on: '', status: 'published', meta_title: '', meta_description: '' };

export default function Form({ post }) {
  const form = useForm({ ...empty, ...post, published_on: post?.published_on ? String(post.published_on).slice(0, 10) : '' });
  const save = () => post ? form.put(`/admin/news/${post.id}`) : form.post('/admin/news');
  return (
    <>
      <header className="admin-top product-top">
        <div>
          <h1>{post ? post.title : 'Add news'}</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span><Link href="/admin/news">News</Link><span aria-hidden="true">/</span>{post ? 'Edit' : 'Add'}</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={save}><Icon name="check" /> Save</button>
      </header>
      <div className="admin-content product-form-page">
        <form className="product-layout" onSubmit={(event) => { event.preventDefault(); save(); }}>
          <div className="product-main">
            <section className="product-card">
              <div className="form-grid two">
                <div className="field"><label>Title</label><input value={form.data.title} onChange={(event) => form.setData('title', event.target.value)} required /></div>
                <div className="field"><label>Slug</label><input value={form.data.slug || ''} onChange={(event) => form.setData('slug', event.target.value)} /><span className="hint">URL-friendly version. Lowercase letters, numbers, and hyphens.</span></div>
              </div>
              <div className="field"><label>Lead</label><textarea rows={3} value={form.data.lead || ''} onChange={(event) => form.setData('lead', event.target.value)} /><span className="hide-note">Hidden on the site when empty</span></div>
              <div className="field"><label>Body</label><textarea rows={10} value={form.data.body || ''} onChange={(event) => form.setData('body', event.target.value)} /></div>
              <div className="field"><label>Category</label><input value={form.data.category || ''} onChange={(event) => form.setData('category', event.target.value)} placeholder="operations" /></div>
            </section>
          </div>
          <aside className="product-side">
            <section className="product-card">
              <h2>Cover</h2>
              <UploadField variant="drop" label="Cover" value={form.data.cover || ''} onChange={(value) => form.setData('cover', value)} hint="JPG, PNG, WEBP, or GIF. An empty cover stays off the article." />
            </section>
            <section className="product-card">
              <h2>Publish</h2>
              <div className="field"><label>Date</label><input type="date" value={form.data.published_on || ''} onChange={(event) => form.setData('published_on', event.target.value)} /></div>
              <div className="field">
                <label>Status</label>
                <div className="status-field"><i aria-hidden="true" /><select value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}><option value="draft">Draft</option><option value="published">Published</option></select></div>
              </div>
            </section>
          </aside>
        </form>
      </div>
    </>
  );
}