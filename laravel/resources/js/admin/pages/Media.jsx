import { router, useForm } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { Icon } from '../icons';
import { mediaUrl } from '../ui';

export default function Media({ items, folders = [] }) {
  const form = useForm({ file: null, alt: '', caption: '' });
  const [folder, setFolder] = useState('all');
  const [query, setQuery] = useState('');
  const [tab, setTab] = useState('all');
  const [selected, setSelected] = useState(null);
  const [checked, setChecked] = useState([]);
  const visible = useMemo(() => items.filter((item) => {
    const inFolder = folder === 'all' || String(item.media_folder_id) === String(folder);
    const needle = query.toLowerCase();
    const text = `${item.caption || ''} ${item.alt || ''} ${item.path || ''}`.toLowerCase();
    const kind = (item.mime || '').startsWith('video') ? 'videos' : (item.mime || '').includes('pdf') ? 'documents' : 'images';
    return inFolder && (!needle || text.includes(needle)) && (tab === 'all' || tab === kind);
  }), [items, folder, query, tab]);
  const active = items.find((item) => item.id === selected) || null;

  return (
    <>
      <header className="admin-top media-page-top">
        <div><h1>Media Gallery</h1></div>
        <div className="admin-top__actions">
          <button className="btn btn-primary media-btn-solid" type="button" onClick={() => document.getElementById('media-file')?.click()}>
            <Icon name="upload" /> Upload Media
          </button>
        </div>
      </header>
      <div className="admin-content media-page">
        <p className="media-lead">Manage and organize mill photographs used on the public site.</p>
        <div className="media-tabs" role="tablist">
          {[['all', 'All Media'], ['images', 'Images'], ['videos', 'Videos'], ['documents', 'Documents']].map(([id, label]) => (
            <button key={id} type="button" className={tab === id ? 'is-active' : ''} onClick={() => setTab(id)}>{label}</button>
          ))}
        </div>
        <div className="media-board">
          <aside className="media-rail">
            <section className="media-folders" aria-label="Folders">
              <div className="media-folders__head"><strong>Folders</strong></div>
              <button type="button" className={`media-folder${folder === 'all' ? ' is-active' : ''}`} onClick={() => setFolder('all')}>
                <Icon name="folder" className="media-folder__icon" />
                <span className="media-folder__name">All media</span>
                <span className="media-folder__count">{items.length}</span>
              </button>
              {folders.map((item) => (
                <button type="button" key={item.id} className={`media-folder${String(folder) === String(item.id) ? ' is-active' : ''}`} onClick={() => setFolder(item.id)}>
                  <Icon name="folder" className="media-folder__icon" />
                  <span className="media-folder__name">{item.name}</span>
                  <span className="media-folder__count">{item.media_count}</span>
                </button>
              ))}
            </section>
            <form className="media-drop" onSubmit={(event) => { event.preventDefault(); form.post('/admin/media', { forceFormData: true }); }}>
              <Icon name="upload" className="media-drop__icon" />
              <strong>Upload an image</strong>
              <span>or</span>
              <input id="media-file" type="file" accept="image/*" hidden onChange={(event) => form.setData('file', event.target.files[0])} />
              <button className="btn btn-primary media-btn-solid" type="button" onClick={() => document.getElementById('media-file')?.click()}>Select Files</button>
              <input value={form.data.alt} onChange={(event) => form.setData('alt', event.target.value)} placeholder="Alt text" aria-label="Alt text" />
              <input value={form.data.caption} onChange={(event) => form.setData('caption', event.target.value)} placeholder="Caption" aria-label="Caption" />
              <button className="btn btn-outline" type="submit">Upload</button>
              <p>Images only. Max file size: 8MB.</p>
            </form>
          </aside>
          <div className="media-stage">
            <div className="media-toolbar">
              <div className="media-toolbar__bulk">
                <span className="media-selected-pill">{checked.length} selected</span>
                <button type="button" className="media-action media-action--danger" disabled={!checked.length} onClick={() => checked.forEach((id) => router.delete(`/admin/media/${id}`))}>Delete</button>
              </div>
              <div className="media-toolbar__end">
                <input type="search" value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Filter" aria-label="Filter media" />
              </div>
            </div>
            <div className="media-library">
              {visible.map((item) => (
                <article key={item.id} className={`media-card${selected === item.id ? ' is-selected' : ''}`} onClick={() => setSelected(item.id)}>
                  <input className="media-card__check" type="checkbox" checked={checked.includes(item.id)} aria-label={`Select ${item.caption || item.path}`} onChange={(event) => {
                    event.stopPropagation();
                    setChecked((current) => event.target.checked ? [...current, item.id] : current.filter((id) => id !== item.id));
                  }} />
                  <img src={mediaUrl(item.path)} alt={item.alt || ''} />
                  <div className="media-card__meta">
                    <strong>{(item.path || '').split('/').pop()}</strong>
                    <small>{item.caption || item.alt || 'No caption'}</small>
                  </div>
                </article>
              ))}
            </div>
          </div>
          <aside className="media-details" aria-label="File details">
            <h2>File details</h2>
            {active ? <img alt={active.alt || ''} src={mediaUrl(active.path)} /> : null}
            <dl>
              <div><dt>Filename</dt><dd>{active ? (active.path || '').split('/').pop() : 'Select a file'}</dd></div>
              <div><dt>File type</dt><dd>{active?.mime || '—'}</dd></div>
              <div><dt>Folder</dt><dd>{active?.folder?.name || folders.find((item) => item.id === active?.media_folder_id)?.name || '—'}</dd></div>
              <div><dt>Dimensions</dt><dd>{active?.width && active?.height ? `${active.width} × ${active.height}` : '—'}</dd></div>
            </dl>
            <div className="field"><label>Alt text</label><input value={active?.alt || ''} readOnly /></div>
            <div className="field"><label>Caption</label><input value={active?.caption || ''} readOnly /><span className="hide-note">Hidden on the site when empty</span></div>
            {active ? <button className="btn btn-outline" type="button" onClick={() => router.delete(`/admin/media/${active.id}`)}>Remove record</button> : null}
          </aside>
        </div>
      </div>
    </>
  );
}
