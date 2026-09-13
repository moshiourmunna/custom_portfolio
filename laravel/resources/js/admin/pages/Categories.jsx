import { useForm } from '@inertiajs/react';
import { Icon } from '../icons';
import { ImagePick } from '../ui';

function Row({ category }) {
  const form = useForm({ name: category.name, text: category.text || '', href: category.href || '', image: category.image || '' });
  return (
    <form className="panel" onSubmit={(event) => { event.preventDefault(); form.put(`/admin/categories/${category.id}`); }}>
      <h2>{category.name} <small>{category.products_count} products</small></h2>
      <div className="form-grid two">
        <div className="field"><label>Name</label><input value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} /></div>
        <div className="field"><label>Link</label><input value={form.data.href} onChange={(event) => form.setData('href', event.target.value)} /></div>
        <div className="field"><label>Text</label><textarea rows={3} value={form.data.text} onChange={(event) => form.setData('text', event.target.value)} /><span className="hide-note">Hidden on the site when empty</span></div>
        <ImagePick label="Image" value={form.data.image} onChange={(value) => form.setData('image', value)} />
      </div>
      <button className="btn btn-primary btn--labeled btn--with-icon" type="submit"><Icon name="check" /> Save</button>
    </form>
  );
}

export default function Categories({ categories }) {
  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>Categories</h1>
          <p className="catalog-crumb">Yarn, woven, and finished ranges. Empty text stays hidden on the site.</p>
        </div>
      </header>
      <div className="admin-content editor-page">
        {categories.map((category) => <Row key={category.id} category={category} />)}
      </div>
    </>
  );
}