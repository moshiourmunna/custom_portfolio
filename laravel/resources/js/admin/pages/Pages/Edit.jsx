import { useForm } from '@inertiajs/react';

export default function Edit({ page }) {
  const form = useForm({ ...page });

  function setField(key, value) {
    form.setData('fields', form.data.fields.map((field) => field.key === key ? { ...field, value } : field));
  }

  function setBlock(id, key, value) {
    form.setData('blocks', form.data.blocks.map((block) => block.id === id ? { ...block, [key]: value } : block));
  }

  return (
    <>
      <header className="admin-top">
        <div><h1>{page.title}</h1><p>{page.slug}</p></div>
        <button className="btn btn-primary" type="button" onClick={() => form.put(`/admin/pages/${page.id}`)}>Save page</button>
      </header>
      <div className="admin-content">
        {['title', 'eyebrow', 'lead', 'line', 'image', 'card_title', 'card_lead', 'commitment_title', 'status', 'meta_title', 'meta_description'].map((key) => (
          <div className="field" key={key}>
            <label>{key}</label>
            <input value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />
          </div>
        ))}
        <div className="field"><label>body</label><textarea value={form.data.body || ''} onChange={(event) => form.setData('body', event.target.value)} /></div>
        {form.data.fields.map((field) => (
          <div className="field" key={field.key}>
            <label>{field.key}</label>
            <textarea value={field.value || ''} onChange={(event) => setField(field.key, event.target.value)} />
            <span className="hide-note">Hidden on the site when empty</span>
          </div>
        ))}
        {form.data.blocks.map((block) => (
          <section className="panel" key={block.id}>
            <h2>{block.group}</h2>
            {['title', 'text', 'value', 'suffix', 'year', 'role', 'initials', 'note', 'href', 'image'].map((key) => (
              <div className="field" key={key}>
                <label>{key}</label>
                <input value={block[key] || ''} onChange={(event) => setBlock(block.id, key, event.target.value)} />
              </div>
            ))}
          </section>
        ))}
      </div>
    </>
  );
}
