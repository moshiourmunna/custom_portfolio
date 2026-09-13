const ICONS = {
  dashboard: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1z"/></svg>',
  home: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 11.5 12 5l8 6.5"/><path d="M7 10.5V19h10v-8.5"/></svg>',
  pages: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.5h7l5 5V20a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1z"/><path d="M14 3.5V9h5"/></svg>',
  products: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 7.5 12 3.5l8.5 4v9L12 20.5l-8.5-4z"/><path d="M12 12.5 3.5 7.5M12 12.5l8.5-5M12 12.5V20.5"/></svg>',
  categories: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12V5h7l9 9-7 7z"/><circle cx="8.5" cy="8.5" r="1" fill="currentColor" stroke="none"/></svg>',
  gallery: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5" width="17" height="14" rx="1.5"/><circle cx="8.5" cy="10" r="1.2" fill="currentColor" stroke="none"/><path d="m6.5 16 3-3 2.2 2 2.4-2.6L18 16"/></svg>',
  news: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 5h11v14H5z"/><path d="M16 8h3v11H8"/><path d="M7.5 9h6M7.5 12h6M7.5 15h4"/></svg>',
  careers: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="7" width="17" height="12" rx="1.5"/><path d="M9 7V5.8A1.8 1.8 0 0 1 10.8 4h2.4A1.8 1.8 0 0 1 15 5.8V7"/><path d="M3.5 12h17"/></svg>',
  inquiries: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.5h16v11H4z"/><path d="m4 7 8 6 8-6"/></svg>',
  media: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="14" rx="2"/><circle cx="9" cy="10" r="1.3" fill="currentColor" stroke="none"/><path d="m7 16 3.2-3.2 2.3 2.2L15 12l3 4"/></svg>',
  settings: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 3.5v2.2M12 18.3v2.2M3.5 12h2.2M18.3 12h2.2M6.1 6.1l1.6 1.6M16.3 16.3l1.6 1.6M17.9 6.1l-1.6 1.6M7.7 16.3l-1.6 1.6"/></svg>',
  logout: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 7V5.5A1.5 1.5 0 0 1 11.5 4h7A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 10 18.5V17"/><path d="M4 12h10M11 8.5 14.5 12 11 15.5"/></svg>',
  bell: '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 9.5a5.5 5.5 0 0 1 11 0c0 4.2 1.2 5.5 1.2 5.5H5.3S6.5 13.7 6.5 9.5z"/><path d="M10 18.5a2 2 0 0 0 4 0"/></svg>',
  user: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="9" r="3"/><path d="M6 19v-.5A4.5 4.5 0 0 1 10.5 14h3A4.5 4.5 0 0 1 18 18.5V19"/></svg>',
  calendar: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M8 3.5v3M16 3.5v3M3.5 10h17"/></svg>',
  chevron: '<svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg>',
  search: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>',
  filter: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16l-6 7.5V19l-4 1.5v-8z"/></svg>',
  plus: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>',
  edit: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10.5-10.5a1.8 1.8 0 0 0-2.5-2.5L5.5 17.5 4 20z"/><path d="m13.5 6.5 4 4"/></svg>',
  trash: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14"/><path d="M9 7V5h6v2"/><path d="M8 7l.8 12h6.4L16 7"/></svg>',
  view: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6z"/><circle cx="12" cy="12" r="2.4"/></svg>',
  back: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6 9 12l6 6"/></svg>',
  image: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="14" rx="2"/><circle cx="9" cy="10" r="1.3"/><path d="m7 16 3.2-3.2 2.3 2.2L15 12l3 4"/></svg>',
  up: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 14 6-6 6 6"/></svg>',
  down: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 10 6 6 6-6"/></svg>',
  check: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>',
  close: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17"/></svg>',
  box: '<svg viewBox="0 0 24 24"><path d="M4 8.5 12 4.5l8 4v7L12 19.5 4 15.5v-7z"/><path d="M12 12.5 20 8.5M12 12.5V19.5M12 12.5 4 8.5"/></svg>',
  chat: '<svg viewBox="0 0 24 24"><path d="M5 6.5h14v9.2a1.5 1.5 0 0 1-1.5 1.5H8l-3.2 2.4V6.5z"/><path d="M8.5 10.2h7M8.5 13.2h4.5"/></svg>',
  paper: '<svg viewBox="0 0 24 24"><path d="M6 5.5h9.5v13H7.2A1.7 1.7 0 0 1 5.5 16.8V7A1.5 1.5 0 0 1 7 5.5z"/><path d="M15.5 8.5H18a1.5 1.5 0 0 1 1.5 1.5v7.2a1.3 1.3 0 0 1-1.3 1.3H15.5"/><path d="M8.2 9.2h5M8.2 12.2h5M8.2 15.2h3.2"/></svg>',
  photo: '<svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><circle cx="9" cy="10" r="1.3"/><path d="m7 16 3.2-3.2 2.3 2.2L15 12l3 4"/></svg>',
  upload: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18.5h10a3.5 3.5 0 0 0 .4-7 4.5 4.5 0 0 0-8.7-1.4A3.2 3.2 0 0 0 7 18.5z"/><path d="M12 16V9.5"/><path d="m9.2 11.8 2.8-2.8 2.8 2.8"/></svg>',
  folder: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.2 7.2h5.4l1.7 1.9h10.5v8.4a1.6 1.6 0 0 1-1.6 1.6H4.8a1.6 1.6 0 0 1-1.6-1.6V7.2z"/></svg>',
};

export function Icon({ name, className }) {
  const svg = ICONS[name] || '';
  const html = className ? svg.replace('<svg ', `<svg class="${className}" `) : svg;
  return <span style={{ display: 'contents' }} dangerouslySetInnerHTML={{ __html: html }} />;
}

export function IconBtn({ kind, label, href, onClick, danger, external }) {
  const className = `icon-btn icon-btn--${kind}${danger ? ' icon-btn--danger' : ''}`;
  const inner = <Icon name={kind} />;
  if (href) {
    return <a className={className} href={href} title={label} aria-label={label} target={external ? '_blank' : undefined} rel={external ? 'noreferrer' : undefined}>{inner}</a>;
  }
  return <button className={className} type="button" title={label} aria-label={label} onClick={onClick}>{inner}</button>;
}
