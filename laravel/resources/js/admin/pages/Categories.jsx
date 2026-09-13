import { useForm } from '@inertiajs/react';

function Row({ category }) {
  const form = useForm({ name: category.name, text: category.text || '', href: category.href || '', image: category.image || '' });
  return (
    <form className="panel" onSubmit={(event) => { event.preventDefault(); form.put(`/admin/categories/${category.id}`); }}>
      <h2>{category.name} <small>{category.products_count} products</small></h2>
      {['name', 'text', 'href', 'image'].map((key) => (
        <div className="field" key={key}><label>{key}</label><input value={form.data[key]} onChange={(event) => form.setData(key, event.target.value)} /></div>
      ))}
      <button className="btn btn-primary" type="submit">Save</button>
    </form>
  );
}

export default function Categories({ categories }) {
  return (
    <>
      <header className="admin-top"><h1>Categories</h1></header>
      <div className="admin-content">{categories.map((category) => <Row key={category.id} category={category} />)}</div>
    </>
  );
}
