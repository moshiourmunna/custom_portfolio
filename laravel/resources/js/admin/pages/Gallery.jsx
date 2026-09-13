import { router, useForm } from '@inertiajs/react';
import { Icon, IconBtn } from '../icons';
import { Actions, Catalog, Thumb, mediaUrl } from '../ui';

export default function Gallery({ items, media }) {
  const form = useForm({ album: 'Factory', image: media[0]?.path || '', alt: '', caption: '' });
  return (
    <>
      <Catalog
        title="Gallery"
        viewHref="/gallery"
        columns={['Image', 'Album', 'Caption', 'Actions']}
        searchKeys={['album', 'caption', 'alt']}
        rows={items.map((item) => ({
          id: item.id,
          album: item.album,
          caption: item.caption,
          alt: item.alt,
          cells: [
            <td key="image"><Thumb path={item.image} alt={item.alt} /></td>,
            <td key="album">{item.album}</td>,
            <td key="caption">{item.caption}</td>,
            <td key="actions">
              <Actions>
                <IconBtn kind="view" label="View image" href={mediaUrl(item.image)} external />
                <IconBtn kind="trash" label="Delete" danger onClick={() => router.delete(`/admin/gallery/${item.id}`)} />
              </Actions>
            </td>,
          ],
        }))}
      />
      <div className="admin-content" style={{ paddingTop: 0 }}>
        <form className="panel" onSubmit={(event) => { event.preventDefault(); form.post('/admin/gallery'); }}>
          <h2>Add item</h2>
          <div className="form-grid two">
            <div className="field"><label>Album</label><input value={form.data.album} onChange={(event) => form.setData('album', event.target.value)} /></div>
            <div className="field">
              <label>Image</label>
              <select value={form.data.image} onChange={(event) => form.setData('image', event.target.value)}>
                {media.map((file) => <option key={file.id} value={file.path}>{file.caption || file.path}</option>)}
              </select>
            </div>
            <div className="field"><label>Alt</label><input value={form.data.alt} onChange={(event) => form.setData('alt', event.target.value)} /></div>
            <div className="field"><label>Caption</label><input value={form.data.caption} onChange={(event) => form.setData('caption', event.target.value)} /></div>
          </div>
          <button className="btn btn-primary btn--labeled btn--with-icon" type="submit"><Icon name="plus" /> Add item</button>
        </form>
      </div>
    </>
  );
}