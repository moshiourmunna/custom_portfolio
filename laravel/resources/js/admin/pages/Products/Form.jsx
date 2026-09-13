import { useForm } from '@inertiajs/react';

const empty = { title: '', slug: '', product_category_id: '', summary: '', description: '', count: '', weave: '', finish: '', construction: '', gsm: '', status: 'published', image: '', meta_title: '', meta_description: '' };

export default function Form({ product, categories }) {
  const form = useForm({ ...empty, ...product, product_category_id: product?.product_category_id || '' });

  return (
    <>
      <header className="admin-top">
        <h1>{product ? 'Edit product' : 'New product'}</h1>
        <button className="btn btn-primary" type="button" onClick={() => product ? form.put(`/admin/products/${product.id}`) : form.post('/admin/products')}>Save</button>
      </header>
      <div className="admin-content">
        <div className="field"><label>Title</label><input value={form.data.title} onChange={(event) => form.setData('title', event.target.value)} /></div>
        <div className="field"><label>Slug</label><input value={form.data.slug || ''} onChange={(event) => form.setData('slug', event.target.value)} /></div>
        <div className="field">
          <label>Category</label>
          <select value={form.data.product_category_id || ''} onChange={(event) => form.setData('product_category_id', event.target.value)}>
            <option value="">None</option>
            {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
          </select>
        </div>
        {['summary', 'description', 'count', 'weave', 'finish', 'construction', 'gsm', 'image', 'status', 'meta_title', 'meta_description'].map((key) => (
          <div className="field" key={key}>
            <label>{key}</label>
            <input value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />
            <span className="hide-note">Hidden on the site when empty</span>
          </div>
        ))}
      </div>
    </>
  );
}
