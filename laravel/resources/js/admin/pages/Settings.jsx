import { useForm } from '@inertiajs/react';

const keys = ['site_name', 'tagline', 'website', 'canonical_base', 'footer_blurb', 'hours', 'office_address', 'mill_address', 'phone', 'email', 'office_pin', 'mill_pin', 'meta_title', 'meta_description', 'og_image', 'logo', 'favicon', 'theme_primary', 'theme_deep', 'theme_accent', 'theme_surface', 'ga', 'gtm', 'meta_pixel', 'meta_domain', 'gsc', 'bing'];

export default function Settings({ setting }) {
  const form = useForm({
    ...Object.fromEntries(keys.map((key) => [key, setting[key] || ''])),
    maintenance: !!setting.maintenance,
    social_links: setting.social_links || setting.socialLinks || [],
  });

  return (
    <>
      <header className="admin-top">
        <div><h1>Settings</h1><p>Empty social URLs, pins, and tracking IDs stay hidden on the public site.</p></div>
        <button className="btn btn-primary" type="button" onClick={() => form.put('/admin/settings')}>Save settings</button>
      </header>
      <div className="admin-content">
        {keys.map((key) => (
          <div className="field" key={key}>
            <label>{key}</label>
            <input value={form.data[key]} onChange={(event) => form.setData(key, event.target.value)} />
          </div>
        ))}
        <label className="login-check"><input type="checkbox" checked={form.data.maintenance} onChange={(event) => form.setData('maintenance', event.target.checked)} /> Maintenance mode</label>
        {(form.data.social_links || []).map((link, index) => (
          <div className="field" key={link.id}>
            <label>{link.label}</label>
            <input value={link.url || ''} onChange={(event) => {
              const next = [...form.data.social_links];
              next[index] = { ...link, url: event.target.value };
              form.setData('social_links', next);
            }} />
            <span className="hide-note">Hidden on the site when empty</span>
          </div>
        ))}
      </div>
    </>
  );
}
