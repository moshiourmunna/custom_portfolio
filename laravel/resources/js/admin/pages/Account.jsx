import { Link, useForm } from '@inertiajs/react';
import { Icon } from '../icons';

export default function Account({ user }) {
  const form = useForm({
    name: user.name || '',
    email: user.email || '',
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const save = () => {
    form.put('/admin/account', {
      preserveScroll: true,
      onSuccess: () => form.reset('current_password', 'password', 'password_confirmation'),
    });
  };

  return (
    <>
      <header className="admin-top product-top">
        <div>
          <h1>Account</h1>
          <p className="catalog-crumb"><Link href="/admin">Dashboard</Link><span aria-hidden="true">/</span>Account</p>
        </div>
        <button className="btn btn-primary btn--labeled btn--with-icon" type="button" onClick={save} disabled={form.processing}>
          <Icon name="check" /> Save account
        </button>
      </header>
      <div className="admin-content product-form-page">
        <form className="product-layout" onSubmit={(event) => { event.preventDefault(); save(); }}>
          <div className="product-main">
            <section className="product-card account-card">
              <h2>Profile</h2>
              <p className="account-note">These details are stored on your admin user in the database. Email is used to sign in.</p>
              <div className="form-grid two">
                <div className="field">
                  <label htmlFor="account-name">Display name</label>
                  <input id="account-name" value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} autoComplete="name" required />
                  {form.errors.name ? <span className="file-field__error">{form.errors.name}</span> : null}
                </div>
                <div className="field">
                  <label htmlFor="account-email">Email / username</label>
                  <input id="account-email" type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} autoComplete="username" required />
                  {form.errors.email ? <span className="file-field__error">{form.errors.email}</span> : null}
                </div>
              </div>
            </section>
            <section className="product-card account-card">
              <h2>Password</h2>
              <p className="account-note">Enter your current password to save any change. Leave the new password blank to keep the current one.</p>
              <div className="form-grid two">
                <div className="field">
                  <label htmlFor="account-current">Current password</label>
                  <input id="account-current" type="password" value={form.data.current_password} onChange={(event) => form.setData('current_password', event.target.value)} autoComplete="current-password" required />
                  {form.errors.current_password ? <span className="file-field__error">{form.errors.current_password}</span> : null}
                </div>
                <div className="field" aria-hidden="true" />
                <div className="field">
                  <label htmlFor="account-password">New password</label>
                  <input id="account-password" type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} autoComplete="new-password" />
                  <span className="hint">At least 8 characters when changing.</span>
                  {form.errors.password ? <span className="file-field__error">{form.errors.password}</span> : null}
                </div>
                <div className="field">
                  <label htmlFor="account-password-confirm">Confirm new password</label>
                  <input id="account-password-confirm" type="password" value={form.data.password_confirmation} onChange={(event) => form.setData('password_confirmation', event.target.value)} autoComplete="new-password" />
                </div>
              </div>
            </section>
          </div>
          <div className="product-actions">
            <Link className="btn btn-outline btn--labeled" href="/admin">Cancel</Link>
            <button className="btn btn-primary btn--labeled btn--with-icon" type="submit" disabled={form.processing}>
              <Icon name="check" /> Save account
            </button>
          </div>
        </form>
      </div>
    </>
  );
}
