import { router, useForm } from '@inertiajs/react';
import { useMemo, useRef, useState } from 'react';
import { Icon } from '../icons';
import { mediaUrl, uploadMedia } from '../ui';

const ACCEPT = '.jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.mov,.pdf,.doc,.docx,.xls,.xlsx';

function kindOf(item) {
  const mime = item.mime || '';
  const path = (item.path || '').toLowerCase();
  if (mime.startsWith('video') || /\.(mp4|webm|mov)$/.test(path)) return 'videos';
  if (mime.includes('pdf') || mime.includes('word') || mime.includes('sheet') || mime.includes('excel') || /\.(pdf|docx?|xlsx?)$/.test(path)) return 'documents';
  return 'images';
}

function Preview({ item, className = 'media-card__preview' }) {
  const kind = kindOf(item);
  if (kind === 'videos') return <video className={className} src={mediaUrl(item.path)} controls={className.includes('details')} muted={!className.includes('details')} preload="metadata" />;
  if (kind === 'documents') {
    return (
      <div className={`media-card__file ${className}`}>
        <Icon name="paper" />
        <span>{(item.path || '').split('.').pop()?.toUpperCase() || 'File'}</span>
      </div>
    );
  }
  return <img className={className} src={mediaUrl(item.path)} alt={item.alt || ''} />;
}

export default function Media({ items, folders = [] }) {
  const form = useForm({ alt: '', caption: '' });
  const fileRef = useRef(null);
  const [folder, setFolder] = useState('all');
  const [query, setQuery] = useState('');
  const [tab, setTab] = useState('all');
  const [selected, setSelected] = useState(null);
  const [checked, setChecked] = useState([]);
  const [busy, setBusy] = useState(false);
  const [notice, setNotice] = useState('');
  const [error, setError] = useState('');
  const visible = useMemo(() => items.filter((item) => {
    const inFolder = folder === 'all' || String(item.media_folder_id) === String(folder);
    const needle = query.toLowerCase();
    const text = `${item.caption || ''} ${item.alt || ''} ${item.path || ''}`.toLowerCase();
    return inFolder && (!needle || text.includes(needle)) && (tab === 'all' || tab === kindOf(item));
  }), [items, folder, query, tab]);
  const active = items.find((item) => item.id === selected) || null;

  async function uploadFiles(list) {
    const files = [...list].filter(Boolean);
    if (!files.length || busy) return;
    setBusy(true);
    setError('');
    setNotice('');
    const failed = [];
    for (const file of files) {
      try {
        await uploadMedia(file, { alt: form.data.alt, caption: form.data.caption || file.name });
      } catch (err) {
        failed.push(`${file.name}: ${err.message || 'Upload failed.'}`);
      }
    }
    setBusy(false);
    if (fileRef.current) fileRef.current.value = '';
    if (failed.length) setError(failed.join(' '));
    else setNotice(files.length === 1 ? 'File uploaded.' : `${files.length} files uploaded.`);
    router.reload({ preserveScroll: true, only: ['items', 'folders'] });
  }

  return (
    <>
      <header className="admin-top media-page-top">
        <div><h1>Media Gallery</h1></div>
        <div className="admin-top__actions">
          <button className="btn btn-primary media-btn-solid" type="button" disabled={busy} onClick={() => fileRef.current?.click()}>
            <Icon name="upload" /> {busy ? 'Uploading…' : 'Upload Media'}
          </button>
        </div>
      </header>
      <div className="admin-content media-page">
        <p className="media-lead">Images, videos, and documents used on the public site. Images up to 8MB, documents up to 20MB, video up to 80MB.</p>
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
            <form
              className={`media-drop${busy ? ' is-busy' : ''}`}
              onSubmit={(event) => event.preventDefault()}
              onDragOver={(event) => { event.preventDefault(); event.currentTarget.classList.add('is-drag'); }}
              onDragLeave={(event) => event.currentTarget.classList.remove('is-drag')}
              onDrop={(event) => { event.preventDefault(); event.currentTarget.classList.remove('is-drag'); uploadFiles(event.dataTransfer.files); }}
            >
              <Icon name="upload" className="media-drop__icon" />
              <strong>{busy ? 'Uploading…' : 'Upload a file'}</strong>
              <span>image, video, or document</span>
              <input ref={fileRef} type="file" accept={ACCEPT} multiple hidden onChange={(event) => uploadFiles(event.target.files)} />
              <button className="btn btn-primary media-btn-solid" type="button" disabled={busy} onClick={() => fileRef.current?.click()}>Select Files</button>
              <input value={form.data.alt} onChange={(event) => form.setData('alt', event.target.value)} placeholder="Alt text" aria-label="Alt text" />
              <input value={form.data.caption} onChange={(event) => form.setData('caption', event.target.value)} placeholder="Caption" aria-label="Caption" />
              {notice ? <p>{notice}</p> : null}
              {error ? <p className="file-field__error">{error}</p> : null}
              <p>JPG, PNG, WEBP, GIF, MP4, WEBM, MOV, PDF, DOC, DOCX, XLS, XLSX.</p>
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
                  <Preview item={item} />
                  <div className="media-card__meta">
                    <strong>{(item.path || '').split('/').pop()}</strong>
                    <small>{item.caption || item.alt || 'No caption'}</small>
                  </div>
                </article>
              ))}
              {!visible.length ? <p className="hint">No files in this view.</p> : null}
            </div>
          </div>
          <aside className="media-details" aria-label="File details">
            <h2>File details</h2>
            {active ? <Preview item={active} className="media-details__preview" /> : null}
            <dl>
              <div><dt>Filename</dt><dd>{active ? (active.path || '').split('/').pop() : 'Select a file'}</dd></div>
              <div><dt>File type</dt><dd>{active?.mime || '—'}</dd></div>
              <div><dt>Folder</dt><dd>{active?.folder?.name || folders.find((item) => item.id === active?.media_folder_id)?.name || '—'}</dd></div>
              <div><dt>Dimensions</dt><dd>{active?.width && active?.height ? `${active.width} × ${active.height}` : '—'}</dd></div>
            </dl>
            <div className="field"><label>Alt text</label><input value={active?.alt || ''} readOnly /></div>
            <div className="field"><label>Caption</label><input value={active?.caption || ''} readOnly /><span className="hide-note">Hidden on the site when empty</span></div>
            {active ? <a className="btn btn-outline btn--labeled" href={mediaUrl(active.path)} target="_blank" rel="noreferrer">Open file</a> : null}
            {active ? <button className="btn btn-outline" type="button" onClick={() => router.delete(`/admin/media/${active.id}`)}>Remove record</button> : null}
          </aside>
        </div>
      </div>
    </>
  );
}
