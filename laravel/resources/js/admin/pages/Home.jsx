import { useForm } from '@inertiajs/react';

export default function Home({ page }) {
  const form = useForm({ ...page });
  const groups = [...new Set(form.data.blocks.map((block) => block.group))];

  function setField(key, value) {
    form.setData('fields', form.data.fields.map((field) => field.key === key ? { ...field, value } : field));
  }

  function setBlock(id, key, value) {
    form.setData('blocks', form.data.blocks.map((block) => block.id === id ? { ...block, [key]: value } : block));
  }

  return (
    <>
      <header className="admin-top">
        <div><h1>Home</h1><p className="catalog-crumb">Twelve homepage blocks. Empty fields stay hidden on the public site.</p></div>
        <button className="btn btn-primary" type="button" onClick={() => form.put('/admin/home')}>Save home</button>
      </header>
      <div className="admin-content editor-page">
        {form.data.fields.map((field) => (
          <div className="field" key={field.key}>
            <label>{field.key}</label>
            <input value={field.value || ''} onChange={(event) => setField(field.key, event.target.value)} />
            <span className="hide-note">Hidden on the site when empty</span>
          </div>
        ))}
        {groups.map((group) => (
          <section className="panel" key={group}>
            <h2>{group}</h2>
            {form.data.blocks.filter((block) => block.group === group).map((block) => (
              <div className="form-grid two" key={block.id}>
                {['title', 'text', 'value', 'suffix', 'meta', 'href', 'link_label', 'image'].map((key) => (
                  <div className="field" key={key}>
                    <label>{key}</label>
                    <input value={block[key] || ''} onChange={(event) => setBlock(block.id, key, event.target.value)} />
                  </div>
                ))}
              </div>
            ))}
          </section>
        ))}
      </div>
    </>
  );
}
