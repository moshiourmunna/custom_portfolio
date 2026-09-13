import { router, useForm } from '@inertiajs/react';

export default function Media({ items }) {
  const form = useForm({ file: null, alt: '', caption: '' });
  return (
    <>
      <header className="admin-top"><h1>Media</h1></header>
      <div className="admin-content">
        <form className="panel" onSubmit={(event) => { event.preventDefault(); form.post('/admin/media', { forceFormData: true }); }}>
          <div className="field"><label>File</label><input type="file" accept="image/*" onChange={(event) => form.setData('file', event.target.files[0])} /></div>
          <div className="field"><label>Alt</label><input value={form.data.alt} onChange={(event) => form.setData('alt', event.target.value)} /></div>
          <div className="field"><label>Caption</label><input value={form.data.caption} onChange={(event) => form.setData('caption', event.target.value)} /></div>
          <button className="btn btn-primary" type="submit">Upload</button>
        </form>
        <div className="media-grid">
          {items.map((item) => (
            <figure key={item.id}>
              <img src={`/storage/${item.path}`} alt={item.alt || ''} width="160" />
              <figcaption>{item.caption || item.path}</figcaption>
              <button type="button" onClick={() => router.delete(`/admin/media/${item.id}`)}>Remove</button>
            </figure>
          ))}
        </div>
      </div>
    </>
  );
}
