import { Link, router, useForm } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import { Icon } from '../icons';
import { Field, ImagePick, Panel, Repeater, Status, bindBlocks } from '../ui';

const SECTION_DEFS = [
  { id: 'hero', title: 'Hero', summary: 'Brand hero, headline, and primary actions', fields: [
    ['hero_image', 'Image', 'image'],
    ['hero_headline', 'Headline'],
    ['hero_lead', 'Lead'],
    ['hero_primary_label', 'Primary button'],
    ['hero_primary_href', 'Primary link', 'text', false],
    ['hero_secondary_label', 'Secondary button'],
    ['hero_secondary_href', 'Secondary link', 'text', false],
  ] },
  { id: 'stats', title: 'Stats', summary: 'Mill highlights strip', repeater: ['stats', ['value', 'suffix', 'title']] },
  { id: 'products', title: 'Product row', summary: 'Yarn, woven, and finished ranges', repeater: ['products', ['title', 'text', 'href']] },
  { id: 'insights', title: 'Insight panels', summary: 'News and sustainability teasers', repeater: ['insights', ['title', 'text', 'href', 'link_label', 'image']] },
  { id: 'why', title: 'Why', summary: 'Trust reasons band', fields: [['why_title', 'Title'], ['why_lead', 'Lead']], repeater: ['why_items', ['title', 'text']] },
  { id: 'integration', title: 'Integration', summary: 'Process steps band', fields: [['integration_title', 'Title'], ['integration_lead', 'Lead']], repeater: ['steps', ['title', 'text', 'meta']] },
  { id: 'facilities', title: 'Facilities teaser', summary: 'Campus capacity teaser', fields: [['facilities_title', 'Title'], ['facilities_lead', 'Lead'], ['facilities_image', 'Image', 'image']], repeater: ['facility_bullets', ['title', 'text']] },
  { id: 'quality', title: 'Quality teaser', summary: 'Quality and certification points', fields: [['quality_title', 'Title'], ['quality_lead', 'Lead']], repeater: ['quality_points', ['title', 'text']] },
  { id: 'markets', title: 'Markets', summary: 'Markets we serve', fields: [['markets_title', 'Title'], ['markets_lead', 'Lead']], repeater: ['markets', ['title', 'text']] },
  { id: 'gallery', title: 'Gallery teaser', summary: 'Photo strip into the gallery', fields: [['gallery_title', 'Title'], ['gallery_lead', 'Lead']], repeater: ['gallery_strip', ['title', 'text', 'image', 'href']] },
  { id: 'careers', title: 'Careers teaser', summary: 'Careers callout', fields: [['careers_title', 'Title'], ['careers_lead', 'Lead'], ['careers_button', 'Button']] },
  { id: 'cta', title: 'CTA', summary: 'Closing quote request band', fields: [['cta_title', 'Title'], ['cta_lead', 'Lead']] },
];

const DEFAULT_ORDER = SECTION_DEFS.map((section) => section.id);
const SECTION_MAP = Object.fromEntries(SECTION_DEFS.map((section) => [section.id, section]));

function readStatus(fields) {
  const raw = (fields || []).find((field) => field.key === 'section_status')?.value;
  if (!raw) return {};
  try {
    const parsed = JSON.parse(raw);
    return parsed && typeof parsed === 'object' ? parsed : {};
  } catch {
    return {};
  }
}

function readOrder(fields) {
  const raw = (fields || []).find((field) => field.key === 'section_order')?.value;
  let order = DEFAULT_ORDER;
  if (raw) {
    try {
      const parsed = JSON.parse(raw);
      if (Array.isArray(parsed)) {
        order = parsed.filter((id) => SECTION_MAP[id]);
      }
    } catch {
      order = DEFAULT_ORDER;
    }
  }
  DEFAULT_ORDER.forEach((id) => {
    if (!order.includes(id)) order = [...order, id];
  });
  return order;
}

function itemCount(section, form) {
  let count = 0;
  if (section.fields) {
    count += section.fields.filter(([key]) => {
      const value = (form.data.fields || []).find((field) => field.key === key)?.value;
      return value !== undefined && value !== null && String(value).trim() !== '';
    }).length;
  }
  if (section.repeater) {
    count += form.data.blocks.filter((block) => block.group === section.repeater[0]).length;
  }
  return count;
}

function moveItem(list, from, to) {
  if (to < 0 || to >= list.length || from === to) return list;
  const next = [...list];
  const [item] = next.splice(from, 1);
  next.splice(to, 0, item);
  return next;
}

export default function Home({ page }) {
  const form = useForm({ ...page, fields: page.fields || [], blocks: page.blocks || [] });
  const api = bindBlocks(form);
  const [openId, setOpenId] = useState(null);
  const [order, setOrder] = useState(() => readOrder(page.fields));
  const [dragId, setDragId] = useState(null);
  const [overId, setOverId] = useState(null);
  const [savingOrder, setSavingOrder] = useState(false);
  const statusMap = useMemo(() => readStatus(page.fields), [page.fields]);
  const serverOrder = useMemo(() => readOrder(page.fields), [page.fields]);
  const rows = order.map((id) => SECTION_MAP[id]).filter(Boolean);
  const open = SECTION_MAP[openId] || null;
  const activeCount = rows.filter((section) => statusMap[section.id] !== false).length;
  const openIndex = open ? order.indexOf(open.id) + 1 : null;

  useEffect(() => {
    setOrder(serverOrder);
  }, [serverOrder]);

  function isActive(id) {
    return statusMap[id] !== false;
  }

  function persistOrder(next) {
    setOrder(next);
    setSavingOrder(true);
    router.put('/admin/home/sections/order', { order: next }, {
      preserveScroll: true,
      onFinish: () => setSavingOrder(false),
    });
  }

  function toggle(section) {
    router.put('/admin/home/sections', { section: section.id, active: !isActive(section.id) }, { preserveScroll: true });
  }

  function nudge(index, direction) {
    persistOrder(moveItem(order, index, index + direction));
  }

  function onDragStart(event, id) {
    setDragId(id);
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', id);
  }

  function onDragOver(event, id) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    if (id !== overId) setOverId(id);
  }

  function onDrop(event, targetId) {
    event.preventDefault();
    const sourceId = dragId || event.dataTransfer.getData('text/plain');
    setDragId(null);
    setOverId(null);
    if (!sourceId || sourceId === targetId) return;
    const from = order.indexOf(sourceId);
    const to = order.indexOf(targetId);
    if (from < 0 || to < 0) return;
    persistOrder(moveItem(order, from, to));
  }

  function onDragEnd() {
    setDragId(null);
    setOverId(null);
  }

  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>Home</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span>Home builder</p>
        </div>
        <div className="admin-top__actions">
          <a className="btn btn-outline btn--labeled" href="/" target="_blank" rel="noreferrer">View site</a>
          {open ? (
            <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={() => form.put('/admin/home')} disabled={form.processing}>
              <Icon name="check" /> Save section
            </button>
          ) : null}
        </div>
      </header>
      <div className="admin-content catalog-page home-builder">
        <div className="list-stats">
          <span><strong>{rows.length}</strong> sections</span>
          <span><strong>{activeCount}</strong> active</span>
          <span><strong>{rows.length - activeCount}</strong> hidden</span>
          {savingOrder ? <span className="is-saving">Saving order…</span> : null}
        </div>
        <section className="catalog-card" aria-label="Homepage sections">
          <div className="builder-head">
            <div>
              <h2>Page sections</h2>
              <p className="catalog-note">Drag the handle to reorder. Use arrows on smaller screens. Toggle Active to show or hide on the public homepage.</p>
            </div>
          </div>
          <div className="catalog-table-wrap">
            <table className="catalog-table builder-table">
              <thead>
                <tr>
                  <th className="builder-col-drag"><span className="sr-only">Move</span></th>
                  <th>#</th>
                  <th>Section</th>
                  <th>Items</th>
                  <th>Status</th>
                  <th>Active</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {rows.map((section, index) => {
                  const active = isActive(section.id);
                  const count = itemCount(section, form);
                  return (
                    <tr
                      key={section.id}
                      className={[
                        openId === section.id ? 'is-open' : '',
                        dragId === section.id ? 'is-dragging' : '',
                        overId === section.id && dragId && dragId !== section.id ? 'is-drop' : '',
                      ].filter(Boolean).join(' ')}
                      onDragOver={(event) => onDragOver(event, section.id)}
                      onDrop={(event) => onDrop(event, section.id)}
                    >
                      <td data-label="Move" className="builder-col-drag">
                        <div className="builder-move">
                          <button
                            type="button"
                            className="drag-handle"
                            draggable
                            aria-label={`Drag ${section.title}`}
                            title="Drag to reorder"
                            onDragStart={(event) => onDragStart(event, section.id)}
                            onDragEnd={onDragEnd}
                          >
                            <Icon name="grip" />
                          </button>
                          <div className="builder-nudge">
                            <button type="button" className="icon-btn" aria-label={`Move ${section.title} up`} title="Move up" disabled={index === 0 || savingOrder} onClick={() => nudge(index, -1)}>
                              <Icon name="up" />
                            </button>
                            <button type="button" className="icon-btn" aria-label={`Move ${section.title} down`} title="Move down" disabled={index === rows.length - 1 || savingOrder} onClick={() => nudge(index, 1)}>
                              <Icon name="down" />
                            </button>
                          </div>
                        </div>
                      </td>
                      <td data-label="#">{String(index + 1).padStart(2, '0')}</td>
                      <td data-label="Section">
                        <span className="catalog-title">
                          <strong>{section.title}</strong>
                          <small>{section.summary}</small>
                        </span>
                      </td>
                      <td data-label="Items">{count}</td>
                      <td data-label="Status"><Status value={active ? 'Active' : 'Hidden'} /></td>
                      <td data-label="Active">
                        <button
                          type="button"
                          className={`switch${active ? ' is-on' : ''}`}
                          role="switch"
                          aria-checked={active}
                          aria-label={`${active ? 'Hide' : 'Show'} ${section.title}`}
                          onClick={() => toggle(section)}
                        >
                          <span className="switch__thumb" />
                        </button>
                      </td>
                      <td data-label="Actions">
                        <button
                          type="button"
                          className={`btn btn-outline btn--labeled builder-edit${openId === section.id ? ' is-active' : ''}`}
                          onClick={() => setOpenId((current) => (current === section.id ? null : section.id))}
                        >
                          <Icon name="edit" /> {openId === section.id ? 'Close' : 'Edit'}
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </section>

        {open ? (
          <div className="home-builder__editor" id={`section-${open.id}`}>
            <Panel id={open.id} title={open.title} numbered={openIndex ? String(openIndex).padStart(2, '0') : null}>
              <p className="hint">Empty fields stay hidden on the public site. Save section after editing copy or images.</p>
              {open.fields ? (
                <div className="form-grid two">
                  {open.fields.map(([key, label, type, note]) => (
                    type === 'image'
                      ? <ImagePick key={key} label={label} value={api.field(key)} onChange={(value) => api.setField(key, value)} />
                      : (
                        <Field key={key} label={label} note={note !== false}>
                          <input value={api.field(key)} onChange={(event) => api.setField(key, event.target.value)} />
                        </Field>
                      )
                  ))}
                </div>
              ) : null}
              {open.repeater ? (
                <Repeater
                  rows={form.data.blocks.filter((block) => block.group === open.repeater[0])}
                  keys={open.repeater[1]}
                  onChange={(index, key, value) => {
                    const groupRows = form.data.blocks.filter((block) => block.group === open.repeater[0]);
                    form.setData('blocks', form.data.blocks.map((block) => (block === groupRows[index] ? { ...block, [key]: value } : block)));
                  }}
                  onAdd={() => api.addBlock(open.repeater[0], open.repeater[1])}
                  onRemove={(index) => {
                    const groupRows = form.data.blocks.filter((block) => block.group === open.repeater[0]);
                    form.setData('blocks', form.data.blocks.filter((block) => block !== groupRows[index]));
                  }}
                  onMove={(index, direction) => api.moveBlock(open.repeater[0], index, direction)}
                />
              ) : null}
              <div className="save-bar">
                <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={() => form.put('/admin/home')} disabled={form.processing}>
                  <Icon name="check" /> Save section
                </button>
                <button className="btn btn-outline btn--labeled" type="button" onClick={() => setOpenId(null)}>Close editor</button>
              </div>
            </Panel>
          </div>
        ) : (
          <p className="builder-empty">Select Edit on a section to change its content.</p>
        )}
      </div>
    </>
  );
}
