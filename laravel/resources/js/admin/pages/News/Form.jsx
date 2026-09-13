import { useForm } from '@inertiajs/react';

const empty = { title: '', slug: '', lead: '', body: '', cover: '', category: '', published_on: '', status: 'published', meta_title: '', meta_description: '' };

export default function Form({ post }) {
  const form = useForm({ ...empty, ...post });
  return (
    <>
      <header className="admin-top">
        <h1>{post ? 'Edit news' : 'New post'}</h1>
        <button className="btn btn-primary" type="button" onClick={() => post ? form.put(`/admin/news/${post.id}`) : form.post('/admin/news')}>Save</button>
      </header>
      <div className="admin-content">
        {Object.keys(empty).map((key) => (
          <div className="field" key={key}>
            <label>{key}</label>
            {key === 'body' || key === 'lead'
              ? <textarea value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />
              : <input value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />}
          </div>
        ))}
      </div>
    </>
  );
}
