import { useForm } from '@inertiajs/react';
import { Editor, Field, ImagePick, Panel, Repeater, bindBlocks } from '../ui';

const sections = [
  { id: 'hero', n: '1', title: 'Hero', fields: [
    ['hero_image', 'Image', 'image'],
    ['hero_headline', 'Headline'],
    ['hero_lead', 'Lead'],
    ['hero_primary_label', 'Primary button'],
    ['hero_primary_href', 'Primary link', 'text', false],
    ['hero_secondary_label', 'Secondary button'],
    ['hero_secondary_href', 'Secondary link', 'text', false],
  ] },
  { id: 'stats', n: '2', title: 'Stats', repeater: ['stats', ['value', 'suffix', 'title']] },
  { id: 'products', n: '3', title: 'Product row', repeater: ['products', ['title', 'text', 'href']] },
  { id: 'insights', n: '4', title: 'Insight panels', repeater: ['insights', ['title', 'text', 'href', 'link_label', 'image']] },
  { id: 'why', n: '5', title: 'Why', fields: [['why_title', 'Title'], ['why_lead', 'Lead']], repeater: ['why_items', ['title', 'text']] },
  { id: 'integration', n: '6', title: 'Integration', fields: [['integration_title', 'Title'], ['integration_lead', 'Lead']], repeater: ['steps', ['title', 'text', 'meta']] },
  { id: 'facilities', n: '7', title: 'Facilities teaser', fields: [['facilities_title', 'Title'], ['facilities_lead', 'Lead'], ['facilities_image', 'Image', 'image']], repeater: ['facility_bullets', ['title', 'text']] },
  { id: 'quality', n: '8', title: 'Quality teaser', fields: [['quality_title', 'Title'], ['quality_lead', 'Lead']], repeater: ['quality_points', ['title', 'text']] },
  { id: 'markets', n: '9', title: 'Markets', fields: [['markets_title', 'Title'], ['markets_lead', 'Lead']], repeater: ['markets', ['title', 'text']] },
  { id: 'gallery', n: '10', title: 'Gallery teaser', fields: [['gallery_title', 'Title'], ['gallery_lead', 'Lead']], repeater: ['gallery_strip', ['title', 'text', 'image', 'href']] },
  { id: 'careers', n: '11', title: 'Careers teaser', fields: [['careers_title', 'Title'], ['careers_lead', 'Lead'], ['careers_button', 'Button']] },
  { id: 'cta', n: '12', title: 'CTA', fields: [['cta_title', 'Title'], ['cta_lead', 'Lead']] },
];

export default function Home({ page }) {
  const form = useForm({ ...page, fields: page.fields || [], blocks: page.blocks || [] });
  const api = bindBlocks(form);

  return (
    <Editor title="Home" crumb="Home" viewHref="/" onSave={() => form.put('/admin/home')} saving={form.processing} hint="Twelve homepage blocks. Empty fields stay hidden on the public site.">
      {sections.map((section) => (
        <Panel id={section.id} title={section.title} numbered={section.n} key={section.id}>
          {section.fields ? (
            <div className="form-grid two">
              {section.fields.map(([key, label, type, note]) => (
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
          {section.repeater ? (
            <Repeater
              rows={form.data.blocks.filter((block) => block.group === section.repeater[0])}
              keys={section.repeater[1]}
              onChange={(index, key, value) => {
                const rows = form.data.blocks.filter((block) => block.group === section.repeater[0]);
                form.setData('blocks', form.data.blocks.map((block) => block === rows[index] ? { ...block, [key]: value } : block));
              }}
              onAdd={() => api.addBlock(section.repeater[0], section.repeater[1])}
              onRemove={(index) => {
                const rows = form.data.blocks.filter((block) => block.group === section.repeater[0]);
                form.setData('blocks', form.data.blocks.filter((block) => block !== rows[index]));
              }}
              onMove={(index, direction) => api.moveBlock(section.repeater[0], index, direction)}
            />
          ) : null}
        </Panel>
      ))}
    </Editor>
  );
}
