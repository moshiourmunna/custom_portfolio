import { useForm } from '@inertiajs/react';
import { Icon } from '../icons';
import { Field, Panel } from '../ui';

const identity = [['site_name', 'Site name'], ['tagline', 'Tagline'], ['website', 'Website']];
const contact = [['hours', 'Hours'], ['office_address', 'Office address'], ['mill_address', 'Mill address'], ['phone', 'Phone'], ['email', 'Email'], ['office_pin', 'Office pin'], ['mill_pin', 'Mill pin']];
const seo = [['meta_title', 'Meta title'], ['meta_description', 'Meta description'], ['canonical_base', 'Canonical base'], ['og_image', 'Open Graph image']];
const tracking = [['ga', 'Google Analytics'], ['gtm', 'Google Tag Manager'], ['meta_pixel', 'Meta pixel'], ['meta_domain', 'Meta domain verification'], ['gsc', 'Google Search Console'], ['bing', 'Bing verification']];

export default function Settings({ setting }) {
  const form = useForm({
    ...Object.fromEntries([...identity, ...contact, ...seo, ...tracking, ['footer_blurb', ''], ['logo', ''], ['favicon', ''], ['theme_primary', ''], ['theme_deep', ''], ['theme_accent', ''], ['theme_surface', '']].map(([key]) => [key, setting[key] || ''])),
    maintenance: !!setting.maintenance,
    social_links: setting.social_links || setting.socialLinks || [],
  });
  const input = (key) => (
    <input value={form.data[key] || ''} onChange={(event) => form.setData(key, event.target.value)} />
  );

  return (
    <>
      <header className="admin-top catalog-top">
        <div>
          <h1>Settings</h1>
          <p>Empty social URLs, pins, and tracking IDs stay hidden on the public site.</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={() => form.put('/admin/settings')}><Icon name="check" /> Save settings</button>
      </header>
      <div className="admin-content editor-page settings-page">
        <div className="editor-layout">
          <aside className="editor-nav">
            <strong>Sections</strong>
            <a href="#identity">Site identity</a>
            <a href="#contact">Contact</a>
            <a href="#seo">SEO</a>
            <a href="#theme">Theme</a>
            <a href="#social">Social</a>
            <a href="#tracking">Tracking</a>
          </aside>
          <div className="editor-main">
            <Panel id="identity" title="Site identity">
              <div className="form-grid two">{identity.map(([key, label]) => <Field key={key} label={label} note={false}>{input(key)}</Field>)}</div>
            </Panel>
            <Panel id="contact" title="Contact information">
              <div className="form-grid two">{contact.map(([key, label]) => <Field key={key} label={label}>{input(key)}</Field>)}</div>
            </Panel>
            <Panel id="seo" title="SEO settings">
              <div className="form-grid two">{seo.map(([key, label]) => <Field key={key} label={label}>{input(key)}</Field>)}</div>
            </Panel>
            <Panel id="theme" title="Theme">
              <div className="form-grid two">
                {[['theme_primary', 'Primary'], ['theme_deep', 'Deep'], ['theme_accent', 'Accent'], ['theme_surface', 'Surface'], ['logo', 'Logo path'], ['favicon', 'Favicon path']].map(([key, label]) => <Field key={key} label={label} note={false}>{input(key)}</Field>)}
              </div>
            </Panel>
            <Panel id="footer" title="Footer">
              <Field label="Footer blurb">{input('footer_blurb')}</Field>
              <label className="login-check"><input type="checkbox" checked={form.data.maintenance} onChange={(event) => form.setData('maintenance', event.target.checked)} /> Maintenance mode</label>
            </Panel>
            <Panel id="social" title="Social profiles">
              {(form.data.social_links || []).map((link, index) => (
                <Field key={link.id || link.label} label={link.label}>
                  <input value={link.url || ''} onChange={(event) => {
                    const next = [...form.data.social_links];
                    next[index] = { ...link, url: event.target.value };
                    form.setData('social_links', next);
                  }} />
                </Field>
              ))}
            </Panel>
            <Panel id="tracking" title="Tracking and verification">
              <div className="form-grid two">{tracking.map(([key, label]) => <Field key={key} label={label}>{input(key)}</Field>)}</div>
            </Panel>
          </div>
        </div>
      </div>
    </>
  );
}