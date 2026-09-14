import { Link, useForm } from '@inertiajs/react';
import { Icon } from '../../icons';
import { UploadField, mediaUrl } from '../../ui';

const empty = { title: '', slug: '', product_category_id: '', summary: '', description: '', count: '', weave: '', finish: '', construction: '', gsm: '', status: 'published', image: '', meta_title: '', meta_description: '' };
const specs = [['count', 'Count'], ['weave', 'Weave'], ['finish', 'Finish'], ['construction', 'Construction'], ['gsm', 'GSM']];

function wrap(form, key, mark) {
  const value = form.data[key] || '';
  form.setData(key, value ? `${mark}${value}${mark}` : value);
}

export default function Form({ product, categories }) {
  const form = useForm({ ...empty, ...product, product_category_id: product?.product_category_id || '', status: product?.status || 'published' });
  const save = () => {
    form.transform((data) => ({ ...data, product_category_id: data.product_category_id || null }));
    product ? form.put(`/admin/products/${product.id}`) : form.post('/admin/products');
  };

  return (
    <>
      <header className="admin-top product-top">
        <div>
          <h1>{product ? product.title : 'Add new product'}</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span><Link href="/admin/products">Products</Link><span aria-hidden="true">/</span>{product ? 'Edit' : 'Add new'}</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={save}><Icon name="check" /> Save</button>
      </header>
      <div className="admin-content product-form-page">
        <form className="product-layout" onSubmit={(event) => { event.preventDefault(); save(); }}>
          <div className="product-main">
            <section className="product-card">
              <div className="form-grid two">
                <div className="field"><label>Product title</label><input value={form.data.title} onChange={(event) => form.setData('title', event.target.value)} placeholder="Enter product title" required /></div>
                <div className="field"><label>Slug</label><input value={form.data.slug || ''} onChange={(event) => form.setData('slug', event.target.value)} placeholder="enter-product-slug" /><span className="hint">URL-friendly version. Lowercase letters, numbers, and hyphens.</span></div>
              </div>
              <div className="field">
                <label>Category</label>
                <select value={form.data.product_category_id || ''} onChange={(event) => form.setData('product_category_id', event.target.value)}>
                  <option value="">Select category</option>
                  {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
                </select>
              </div>
              <div className="field"><label>Summary</label><textarea rows={3} value={form.data.summary || ''} onChange={(event) => form.setData('summary', event.target.value)} /><span className="hide-note">Hidden on the site when empty</span></div>
              <div className="field">
                <label>Description</label>
                <div className="editor">
                  <div className="editor-bar" role="toolbar" aria-label="Description formatting">
                    <span>Paragraph</span>
                    <button type="button" aria-label="Bold" onClick={() => wrap(form, 'description', '**')}><b>B</b></button>
                    <button type="button" aria-label="Italic" onClick={() => wrap(form, 'description', '_')}><i>I</i></button>
                  </div>
                  <textarea rows={6} value={form.data.description || ''} onChange={(event) => form.setData('description', event.target.value)} />
                </div>
              </div>
            </section>
            <section className="product-card">
              <h2>Specifications</h2>
              <div className="spec-table">
                <div className="spec-head"><span>Specification</span><span>Value</span><span /></div>
                {specs.map(([key, label]) => (
                  <div className="spec-row" key={key}>
                    <input value={label} readOnly aria-label="Specification" />
                    <input value={form.data[key] || ''} aria-label={label} onChange={(event) => form.setData(key, event.target.value)} />
                    <button type="button" className="spec-clear" aria-label={`Clear ${label}`} onClick={() => form.setData(key, '')}>×</button>
                  </div>
                ))}
              </div>
              <p className="hide-note">A specification stays off the product page when its value is empty.</p>
            </section>
            <section className="product-card">
              <h2>SEO</h2>
              <div className="field"><label>Meta title</label><input value={form.data.meta_title || ''} maxLength={70} onChange={(event) => form.setData('meta_title', event.target.value)} /><span className="char-count">{(form.data.meta_title || '').length} / 60</span></div>
              <div className="field"><label>Meta description</label><textarea rows={3} maxLength={180} value={form.data.meta_description || ''} onChange={(event) => form.setData('meta_description', event.target.value)} /><span className="char-count">{(form.data.meta_description || '').length} / 160</span></div>
            </section>
          </div>
          <aside className="product-side">
            <section className="product-card">
              <h2>Product image</h2>
              <UploadField variant="drop" label="Product image" value={form.data.image || ''} onChange={(value) => form.setData('image', value)} hint="JPG, PNG, WEBP, or GIF. An empty image stays off the product page." />
            </section>
            {product?.images?.length ? (
              <section className="product-card">
                <h2>Product gallery</h2>
                <div className="product-gallery">
                  {product.images.map((image) => <img key={image.id} src={mediaUrl(image.path)} alt="" />)}
                </div>
              </section>
            ) : null}
            <section className="product-card">
              <h2>Publish</h2>
              <div className="field">
                <label>Status</label>
                <div className={`status-field${form.data.status === 'draft' ? ' is-draft' : ''}`}>
                  <i aria-hidden="true" />
                  <select value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                  </select>
                </div>
              </div>
            </section>
          </aside>
          <div className="product-actions">
            <Link className="btn btn-outline btn--labeled" href="/admin/products">Cancel</Link>
            <button className="btn btn-primary btn--labeled btn--with-icon" type="submit"><Icon name="check" /> Save product</button>
          </div>
        </form>
      </div>
    </>
  );
}