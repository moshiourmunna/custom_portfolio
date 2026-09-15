import { Link } from '@inertiajs/react';
import { Children, cloneElement, isValidElement, useMemo, useRef, useState } from 'react';
import { Icon } from './icons';

export const IMAGE_ACCEPT = 'image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif';

export function mediaUrl(path) {
  if (!path) return '';
  if (/^(https?:|data:|\/)/.test(path)) return path;
  return `/storage/${path}`;
}

export function Field({ label, children, note = true }) {
  return (
    <div className="field">
      <label>{label}</label>
      {children}
      {note ? <span className="hide-note">Hidden on the site when empty</span> : null}
    </div>
  );
}

export function uploadMedia(file, extra = {}) {
  const body = new FormData();
  body.append('file', file);
  Object.entries(extra).forEach(([key, item]) => {
    if (item) body.append(key, item);
  });
  const token = decodeURIComponent((document.cookie.split('; ').find((row) => row.startsWith('XSRF-TOKEN=')) || '').split('=').slice(1).join('='));
  return fetch('/admin/uploads', {
    method: 'POST',
    credentials: 'same-origin',
    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-XSRF-TOKEN': token },
    body,
  }).then(async (response) => {
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.errors?.file?.[0] || data.message || 'Upload failed.');
    return data;
  });
}

export function UploadField({ label, value, onChange, variant = 'pick', hint, accept = IMAGE_ACCEPT, bare = false }) {
  const input = useRef(null);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');

  async function pick(file) {
    if (!file) return;
    setBusy(true);
    setError('');
    try {
      const saved = await uploadMedia(file);
      onChange(saved.path);
    } catch (err) {
      setError(err.message || 'Upload failed.');
    } finally {
      setBusy(false);
      if (input.current) input.current.value = '';
    }
  }

  const fileInput = <input ref={input} type="file" accept={accept} hidden onChange={(event) => pick(event.target.files?.[0])} />;
  const control = variant === 'drop' ? (
    <button type="button" className="product-drop__hit" onClick={() => input.current?.click()} disabled={busy}>
      <Icon name="upload" />
      <strong>{busy ? 'Uploading…' : (value ? 'Replace image' : 'Upload image')}</strong>
          <span>{hint || 'JPG, PNG, WEBP, or GIF. Upload, then save.'}</span>
    </button>
  ) : (
    <button type="button" className="btn btn-outline btn--labeled image-pick__btn" onClick={() => input.current?.click()} disabled={busy}>
      <Icon name="upload" /> {busy ? 'Uploading…' : 'Upload'}
    </button>
  );

  const body = variant === 'drop' ? (
    <div className={`product-drop${busy ? ' is-busy' : ''}`}>
      {value ? <img alt="" src={mediaUrl(value)} /> : null}
      {fileInput}
      {control}
      <input value={value || ''} onChange={(event) => onChange(event.target.value)} placeholder="Or paste an existing path" aria-label={label || 'Image path'} />
      {value ? <button type="button" className="btn btn-outline btn--labeled" onClick={() => onChange('')}>Remove</button> : null}
      {error ? <p className="file-field__error">{error}</p> : null}
    </div>
  ) : (
    <div className={`image-pick${busy ? ' is-busy' : ''}`}>
      {value ? <img alt="" src={mediaUrl(value)} /> : <img alt="" hidden />}
      <input value={value || ''} onChange={(event) => onChange(event.target.value)} placeholder="media/…" aria-label={label || 'Image path'} />
      {fileInput}
      {control}
      {value ? <button type="button" className="icon-btn" aria-label="Remove image" title="Remove image" onClick={() => onChange('')}><Icon name="close" /></button> : null}
      {error ? <p className="file-field__error">{error}</p> : null}
    </div>
  );

  if (bare || !label) return body;
  return <Field label={label}>{body}</Field>;
}

export function ImagePick({ label, value, onChange }) {
  return <UploadField label={label} value={value} onChange={onChange} />;
}

export function Status({ value }) {
  const off = /draft|closed|hidden|inactive/i.test(value || '');
  return <span className={`catalog-status${off ? ' is-off' : ''}`}><i aria-hidden="true" />{value}</span>;
}

export function Catalog({ title, crumb, viewHref, action, stats = [], note, columns, rows, searchKeys = [], facets = [], children }) {
  const [query, setQuery] = useState('');
  const [status, setStatus] = useState('');
  const [facet, setFacet] = useState({});
  const [pageSize, setPageSize] = useState(10);
  const [page, setPage] = useState(1);
  const statuses = [...new Set(rows.map((row) => row.status).filter(Boolean))];
  const filtered = useMemo(() => rows.filter((row) => {
    const hay = searchKeys.map((key) => row[key] || '').join(' ').toLowerCase();
    const matchQuery = !query || hay.includes(query.toLowerCase());
    const matchStatus = !status || row.status === status;
    const matchFacet = facets.every((item) => !facet[item.key] || row[item.key] === facet[item.key]);
    return matchQuery && matchStatus && matchFacet;
  }), [rows, query, status, searchKeys, facets, facet]);
  const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
  const current = Math.min(page, pages);
  const start = (current - 1) * pageSize;
  const visible = filtered.slice(start, start + pageSize);

  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>{title}</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span>{crumb || title}</p>
        </div>
        <div className="admin-top__actions">
          {viewHref ? <a className="btn btn-outline btn--labeled" href={viewHref} target="_blank" rel="noreferrer">View site</a> : null}
          {action}
        </div>
      </header>
      <div className="admin-content catalog-page">
        {stats.length ? (
          <div className="list-stats">
            {stats.map((item) => <span key={item.label}><strong>{item.value}</strong> {item.label}</span>)}
          </div>
        ) : null}
        {children}
        <section className="catalog-card" aria-label={title}>
          <div className="catalog-filters">
            <label className="catalog-search">
              <input type="search" value={query} onChange={(event) => { setQuery(event.target.value); setPage(1); }} placeholder={`Search ${title.toLowerCase()}…`} aria-label={`Search ${title}`} />
              <Icon name="search" />
            </label>
            {facets.map((item) => (
              <select key={item.key} value={facet[item.key] || ''} aria-label={item.label} onChange={(event) => { setFacet((current) => ({ ...current, [item.key]: event.target.value })); setPage(1); }}>
                <option value="">{item.label}</option>
                {item.values.map((value) => <option key={value}>{value}</option>)}
              </select>
            ))}
            {statuses.length ? (
              <select value={status} onChange={(event) => { setStatus(event.target.value); setPage(1); }} aria-label="Status">
                <option value="">Select status</option>
                {statuses.map((item) => <option key={item}>{item}</option>)}
              </select>
            ) : null}
            <button type="button" className="btn btn-outline btn--labeled catalog-reset" onClick={() => { setQuery(''); setStatus(''); setFacet({}); setPage(1); }}>Reset</button>
            <button type="button" className="btn btn-primary btn--labeled catalog-filter"><Icon name="filter" /> Filter</button>
          </div>
          {note ? <p className="catalog-note">{note}</p> : null}
          <div className="catalog-table-wrap">
            <table className="catalog-table">
              <thead><tr>{columns.map((column) => <th key={column}>{column}</th>)}</tr></thead>
              <tbody>
                {visible.map((row) => (
                  <tr key={row.id}>
                    {labeledCells(row.cells, columns)}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <div className="catalog-foot">
            <p>Showing {filtered.length ? start + 1 : 0}–{Math.min(start + pageSize, filtered.length)} of {filtered.length} results</p>
            <div className="catalog-pager">
              <label>Rows
                <select value={pageSize} aria-label="Rows per page" onChange={(event) => { setPageSize(Number(event.target.value)); setPage(1); }}>
                  {[5, 10, 25].map((size) => <option key={size}>{size}</option>)}
                </select>
              </label>
              <div>
                <button type="button" className="btn btn-outline btn--labeled" disabled={current <= 1} onClick={() => setPage(current - 1)}>Prev</button>
                <button type="button" className="btn btn-outline btn--labeled" disabled={current >= pages} onClick={() => setPage(current + 1)}>Next</button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </>
  );
}

function labeledCells(cells, columns) {
  return Children.toArray(cells).map((cell, index) => {
    if (!isValidElement(cell)) return cell;
    const label = columns[index] || '';
    return cloneElement(cell, { 'data-label': cell.props['data-label'] || label });
  });
}

export function Thumb({ path, alt }) {
  if (!path) return null;
  return <img className="list-thumb" src={mediaUrl(path)} alt={alt || ''} />;
}

export function Actions({ children }) {
  return <div className="row-actions">{children}</div>;
}

export function Editor({ title, crumb, viewHref, onSave, saving, hint, children }) {
  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>{title}</h1>
          <p className="catalog-crumb">
            <Link href="/admin">Dashboard</Link>
            <span aria-hidden="true">/</span>
            {crumb}
          </p>
        </div>
        <div className="admin-top__actions">
          {viewHref ? <a className="btn btn-outline btn--labeled" href={viewHref} target="_blank" rel="noreferrer">View site</a> : null}
          <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={onSave} disabled={saving}><Icon name="check" /> Save</button>
        </div>
      </header>
      <div className="admin-content editor-page">
        <div className="editor-layout">
          <aside className="editor-nav">
            <strong>Sections</strong>
            {sectionLinks(children)}
          </aside>
          <div className="editor-main">
            {hint ? <p className="hint">{hint}</p> : null}
            {children}
            <div className="save-bar">
              <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={onSave} disabled={saving}><Icon name="check" /> Save</button>
              <span className="hint">Empty fields stay hidden on the public site.</span>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

function sectionLinks(children) {
  return Children.toArray(children).map((child) => {
    const id = child?.props?.id;
    const title = child?.props?.title || child?.props?.['data-title'];
    if (!id || !title) return null;
    return <a key={id} href={`#${id}`}>{title}</a>;
  });
}

export function Panel({ id, title, numbered, children }) {
  return (
    <section className="panel" id={id} data-title={title}>
      <h2>{numbered ? <span className="section-num">{numbered}</span> : null}{title}</h2>
      {children}
    </section>
  );
}

export function Repeater({ rows, keys, onChange, onAdd, onRemove, onMove }) {
  return (
    <>
      {rows.map((row, index) => (
        <div className="form-grid two" key={row.id || index}>
          {keys.map((key) => (
            <Field key={key} label={key}>
              {key === 'image' ? (
                <UploadField bare value={row[key] || ''} onChange={(next) => onChange(index, key, next)} />
              ) : key === 'text' || key === 'note' ? (
                <textarea rows={3} value={row[key] || ''} onChange={(event) => onChange(index, key, event.target.value)} />
              ) : (
                <input value={row[key] || ''} onChange={(event) => onChange(index, key, event.target.value)} />
              )}
            </Field>
          ))}
          <div className="row-actions">
            <button className="icon-btn" type="button" aria-label="Move up" title="Move up" onClick={() => onMove(index, -1)}><Icon name="up" /></button>
            <button className="icon-btn" type="button" aria-label="Move down" title="Move down" onClick={() => onMove(index, 1)}><Icon name="down" /></button>
            <button className="icon-btn icon-btn--danger" type="button" aria-label="Remove" title="Remove" onClick={() => onRemove(index)}><Icon name="trash" /></button>
          </div>
        </div>
      ))}
      <p><button type="button" className="btn btn-outline btn--labeled" onClick={onAdd}>Add row</button></p>
      <p className="hint">Empty rows are omitted when saved.</p>
    </>
  );
}

export function bindBlocks(form) {
  function setBlock(id, key, value) {
    form.setData('blocks', form.data.blocks.map((block) => block.id === id ? { ...block, [key]: value } : block));
  }
  function addBlock(group, keys) {
    const blank = Object.fromEntries(keys.map((key) => [key, '']));
    form.setData('blocks', [...form.data.blocks, { id: null, group, sort: form.data.blocks.length + 1, ...blank }]);
  }
  function removeBlock(id, index) {
    form.setData('blocks', form.data.blocks.filter((block, item) => (id ? block.id !== id : item !== index)));
  }
  function moveBlock(group, index, direction) {
    const groupRows = form.data.blocks.filter((block) => block.group === group);
    const next = index + direction;
    if (next < 0 || next >= groupRows.length) return;
    const swapped = [...groupRows];
    [swapped[index], swapped[next]] = [swapped[next], swapped[index]];
    const others = form.data.blocks.filter((block) => block.group !== group);
    form.setData('blocks', [...others, ...swapped.map((block, item) => ({ ...block, sort: item + 1 }))]);
  }
  function setField(key, value) {
    const fields = form.data.fields || [];
    if (!fields.some((field) => field.key === key)) {
      form.setData('fields', [...fields, { key, value }]);
      return;
    }
    form.setData('fields', fields.map((field) => field.key === key ? { ...field, value } : field));
  }
  function field(key) {
    return (form.data.fields || []).find((item) => item.key === key)?.value || '';
  }
  return { setBlock, addBlock, removeBlock, moveBlock, setField, field };
}
