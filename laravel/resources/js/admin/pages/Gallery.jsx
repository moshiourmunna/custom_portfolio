import { router, useForm } from '@inertiajs/react';

export default function Gallery({ items, media }) {
  const form = useForm({ album: 'Factory', image: media[0]?.path || '', alt: '', caption: '' });
  return (
    <>
      <header className="admin-top"><h1>Gallery</h1></header>
      <div className="admin-content">
        <form className="panel" onSubmit={(event) => { event.preventDefault(); form.post('/admin/gallery'); }}>
          <div className="field"><label>Album</label><input value={form.data.album} onChange={(event) => form.setData('album', event.target.value)} /></div>
          <div className="field">
            <label>Image</label>
            <select value={form.data.image} onChange={(event) => form.setData('image', event.target.value)}>
              {media.map((file) => <option key={file.id} value={file.path}>{file.caption || file.path}</option>)}
            </select>
          </div>
          <div className="field"><label>Alt</label><input value={form.data.alt} onChange={(event) => form.setData('alt', event.target.value)} /></div>
          <div className="field"><label>Caption</label><input value={form.data.caption} onChange={(event) => form.setData('caption', event.target.value)} /></div>
          <button className="btn btn-primary" type="submit">Add item</button>
        </form>
        <table className="dash-table">
          <tbody>
            {items.map((item) => (
              <tr key={item.id}>
                <td>{item.album}</td>
                <td>{item.caption}</td>
                <td><button type="button" onClick={() => router.delete(`/admin/gallery/${item.id}`)}>Remove</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}
