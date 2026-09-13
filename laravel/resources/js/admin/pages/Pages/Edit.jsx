import { Link } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import { Editor, Field, ImagePick, Panel, Repeater, bindBlocks } from '../../ui';

const views = { success: '/quote-success' };

export default function Edit({ page }) {
  const form = useForm({ ...page, fields: page.fields || [], blocks: page.blocks || [] });
  const api = bindBlocks(form);
  const schema = schemas[page.slug] || fallback(page);
  const view = views[page.slug] || `/${page.slug}`;

  return (
    <Editor
      title={page.title || page.slug}
      crumb={<><Link href="/admin/pages">Pages</Link><span aria-hidden="true"> / </span>{page.title}</>}
      viewHref={view}
      onSave={() => form.put(`/admin/pages/${page.id}`)}
      saving={form.processing}
      hint={schema.hint}
    >
      {schema.panels.map((panel) => (
        <Panel id={panel.id} title={panel.title} key={panel.id}>
          {panel.note ? <p className="hint">{panel.note}</p> : null}
          {panel.link ? <p><Link className="btn btn-outline btn--labeled" href={panel.link.href}>{panel.link.label}</Link></p> : null}
          {panel.fields ? <div className="form-grid two">{panel.fields.map((field) => renderField(form, api, field))}</div> : null}
          {panel.repeater ? (
            <Repeater
              rows={form.data.blocks.filter((block) => block.group === panel.repeater.group)}
              keys={panel.repeater.keys}
              onChange={(index, key, value) => {
                const rows = form.data.blocks.filter((block) => block.group === panel.repeater.group);
                form.setData('blocks', form.data.blocks.map((block) => block === rows[index] ? { ...block, [key]: value } : block));
              }}
              onAdd={() => api.addBlock(panel.repeater.group, panel.repeater.keys)}
              onRemove={(index) => {
                const rows = form.data.blocks.filter((block) => block.group === panel.repeater.group);
                const target = rows[index];
                form.setData('blocks', form.data.blocks.filter((block) => block !== target));
              }}
              onMove={(index, direction) => api.moveBlock(panel.repeater.group, index, direction)}
            />
          ) : null}
        </Panel>
      ))}
    </Editor>
  );
}

function renderField(form, api, field) {
  const value = field.field ? api.field(field.field) : (form.data[field.column] || '');
  const set = (next) => field.field ? api.setField(field.field, next) : form.setData(field.column, next);
  if (field.type === 'image') return <ImagePick key={field.label} label={field.label} value={value} onChange={set} />;
  return (
    <Field key={field.label} label={field.label} note={field.note !== false}>
      {field.type === 'area'
        ? <textarea rows={5} value={value} onChange={(event) => set(event.target.value)} />
        : <input value={value} onChange={(event) => set(event.target.value)} />}
    </Field>
  );
}

const schemas = {
  about: {
    panels: [
      { id: 'heritage', title: 'Heritage', fields: [
        { label: 'Title', field: 'heritage_title' },
        { label: 'Image', column: 'image', type: 'image' },
        { label: 'Opening', field: 'heritage_text', type: 'area' },
        { label: 'Second paragraph', field: 'heritage_more', type: 'area' },
      ] },
      { id: 'vmv', title: 'Vision, mission, values', fields: [
        { label: 'Vision', field: 'vision', type: 'area' },
        { label: 'Mission', field: 'mission', type: 'area' },
        { label: 'Values — one per line', field: 'values', type: 'area' },
      ] },
      { id: 'timeline', title: 'Timeline', repeater: { group: 'timeline', keys: ['year', 'title', 'text'] } },
      { id: 'leaders', title: 'Leadership — initials, no portraits', repeater: { group: 'leaders', keys: ['initials', 'title', 'role', 'text'] } },
    ],
  },
  process: {
    panels: [
      { id: 'hero', title: 'Hero', fields: [
        { label: 'Eyebrow', column: 'eyebrow', note: false },
        { label: 'Title', column: 'title' },
        { label: 'Lead', column: 'lead', type: 'area' },
        { label: 'Hero image', column: 'image', type: 'image' },
        { label: 'Quote', field: 'quote', type: 'area' },
      ] },
      { id: 'stages', title: 'Six stages', repeater: { group: 'stages', keys: ['title', 'text', 'image'] } },
    ],
  },
  facilities: {
    hint: 'These capacity figures stay on the facilities page. They are not the homepage stats.',
    panels: [
      { id: 'hero', title: 'Hero', fields: [
        { label: 'Title', column: 'title' },
        { label: 'Lead', column: 'lead', type: 'area' },
        { label: 'Image', column: 'image', type: 'image' },
      ] },
      { id: 'figures', title: 'Capacity figures', repeater: { group: 'figures', keys: ['value', 'suffix', 'title'] } },
      { id: 'campus', title: 'Campus notes', repeater: { group: 'campus', keys: ['title', 'text'] } },
      { id: 'departments', title: 'Departments', repeater: { group: 'departments', keys: ['title', 'text'] } },
      { id: 'cards', title: 'Department photos', repeater: { group: 'dept_cards', keys: ['title', 'text', 'image'] } },
    ],
  },
  quality: {
    hint: 'Document cards are buyer-request documents, not earned certificates. Do not mark ISO, OEKO-TEX, BSCI, GOTS, or GRS as held.',
    panels: [
      { id: 'hero', title: 'Hero', fields: [
        { label: 'Title', column: 'title' },
        { label: 'Line', column: 'line' },
        { label: 'Lead', column: 'lead', type: 'area' },
      ] },
      { id: 'points', title: 'Process points', repeater: { group: 'points', keys: ['title', 'text'] } },
      { id: 'documents', title: 'Buyer-request documents', repeater: { group: 'documents', keys: ['title', 'note'] } },
    ],
  },
  sustainability: {
    panels: [
      { id: 'page', title: 'Page', fields: [
        { label: 'Title', column: 'title' },
        { label: 'Lead', column: 'lead', type: 'area' },
        { label: 'Line', column: 'line' },
      ] },
      { id: 'pillars', title: 'Pillars', repeater: { group: 'pillars', keys: ['title', 'text', 'href', 'link_label'] } },
      { id: 'commitment', title: 'Commitment', fields: [
        { label: 'Title', column: 'commitment_title' },
        { label: 'Text', column: 'body', type: 'area' },
      ] },
    ],
  },
  contact: {
    panels: [
      { id: 'details', title: 'Contact details', note: 'Addresses, phone, email, and map pins stay in Settings so they are not edited twice.', link: { href: '/admin/settings', label: 'Open settings' } },
      { id: 'copy', title: 'Page copy', fields: [
        { label: 'Title', column: 'title' },
        { label: 'Lead', column: 'lead', type: 'area' },
        { label: 'Card title', column: 'card_title' },
        { label: 'Card lead', column: 'card_lead', type: 'area' },
        { label: 'Hero image', column: 'image', type: 'image' },
      ] },
    ],
  },
  privacy: {
    panels: [{ id: 'privacy', title: 'Privacy', fields: [
      { label: 'Title', column: 'title', note: false },
      { label: 'Last updated', column: 'updated_on', note: false },
      { label: 'Body', column: 'body', type: 'area' },
    ] }],
  },
  terms: {
    panels: [{ id: 'terms', title: 'Terms', fields: [
      { label: 'Title', column: 'title', note: false },
      { label: 'Lead', column: 'lead', type: 'area' },
      { label: 'Body', column: 'body', type: 'area' },
    ] }],
  },
  success: {
    panels: [{ id: 'success', title: 'Quote success', fields: [
      { label: 'Title', column: 'title' },
      { label: 'Message', column: 'body', type: 'area' },
    ] }],
  },
  products: {
    panels: [{ id: 'hero', title: 'Hero', fields: [
      { label: 'Eyebrow', column: 'eyebrow', note: false },
      { label: 'Title', column: 'title' },
      { label: 'Lead', column: 'lead', type: 'area' },
      { label: 'Image', column: 'image', type: 'image' },
    ] }],
  },
  gallery: {
    panels: [{ id: 'copy', title: 'Gallery page', fields: [
      { label: 'Title', column: 'title' },
      { label: 'Lead', column: 'lead', type: 'area' },
    ] }],
  },
  news: {
    panels: [{ id: 'copy', title: 'News page', fields: [
      { label: 'Title', column: 'title' },
      { label: 'Lead', column: 'lead', type: 'area' },
    ] }],
  },
  careers: {
    panels: [{ id: 'copy', title: 'Careers page', fields: [
      { label: 'Title', column: 'title' },
      { label: 'Lead', column: 'lead', type: 'area' },
    ] }],
  },
};

function fallback(page) {
  const groups = [...new Set((page.blocks || []).map((block) => block.group))];
  return {
    panels: [
      { id: 'copy', title: 'Page copy', fields: [
        { label: 'Title', column: 'title' },
        { label: 'Lead', column: 'lead', type: 'area' },
        { label: 'Body', column: 'body', type: 'area' },
        { label: 'Image', column: 'image', type: 'image' },
      ] },
      ...groups.map((group) => ({ id: group, title: group.replaceAll('_', ' '), repeater: { group, keys: ['title', 'text', 'image'] } })),
    ],
  };
}
