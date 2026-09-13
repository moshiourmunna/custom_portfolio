@switch($icon)
  @case('stat-looms')
    <svg viewBox="0 0 32 32"><path d="M4 28V14l6-4v4l6-5 6 5v-4l6 4v14"/><path d="M13 28v-6h6v6"/><path d="M8 18h2M8 22h2M22 18h2M22 22h2"/></svg>
    @break
  @case('stat-yarn')
    <svg viewBox="0 0 32 32"><ellipse cx="16" cy="7.2" rx="7.2" ry="2.3"/><path d="M8.8 7.6 12.4 23.2h7.2L23.2 7.6"/><path d="M10.6 12.4h10.8M11.4 16.2h9.2M12.2 20h7.6"/><path d="M16 23.2v3.4"/></svg>
    @break
  @case('stat-globe')
    <svg viewBox="0 0 32 32"><circle cx="16" cy="16" r="11"/><path d="M5 16h22M16 5c3.2 3.4 4.8 7 4.8 11S19.2 23.6 16 27c-3.2-3.4-4.8-7-4.8-11S12.8 8.4 16 5z"/></svg>
    @break
  @case('stat-people')
    <svg viewBox="0 0 32 32"><circle cx="11" cy="11" r="3.2"/><circle cx="21" cy="12" r="2.6"/><path d="M4.5 25v-1.2A5.2 5.2 0 019.7 18.6h2.6a5.2 5.2 0 015.2 5.2V25"/><path d="M18.2 19.2h2.2a4.4 4.4 0 014.4 4.4V25"/></svg>
    @break
  @case('product-yarn')
    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linejoin="round"><ellipse cx="24" cy="13" rx="9.5" ry="3"/><path d="M14.6 13.2 19.2 33.5h9.6l4.6-20.3"/><path d="M16.4 19.2h15.2M17.4 24h13.2M18.4 28.8h11.2"/><path d="M24 33.5v3.2"/></svg>
    @break
  @case('product-weave')
    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linejoin="round"><rect x="9" y="9" width="30" height="30" rx="1.5"/><path d="M9 16.5h30M9 24h30M9 31.5h30M16.5 9v30M24 9v30M31.5 9v30"/></svg>
    @break
  @case('product-finish')
    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linejoin="round"><path d="M10 31.5c0-2.2 6.2-4.5 14-4.5s14 2.3 14 4.5"/><path d="M10 31.5v5.2c0 2.2 6.2 4.5 14 4.5s14-2.3 14-4.5v-5.2"/><path d="M13 25.2c0-1.8 4.8-3.6 11-3.6s11 1.8 11 3.6"/><path d="M13 25.2v4.2c0 1.8 4.8 3.6 11 3.6s11-1.8 11-3.6v-4.2"/><path d="M16 19.2c0-1.4 3.6-2.8 8-2.8s8 1.4 8 2.8"/><path d="M16 19.2v3.2c0 1.4 3.6 2.8 8 2.8s8-1.4 8-2.8v-3.2"/></svg>
    @break
  @case('leaf')
    <svg viewBox="0 0 160 220" fill="none"><path d="M78 208c2-46 8-78 22-112" stroke="#6d9a90" stroke-width="1.6"/><path d="M86 168c18-6 34-6 52 2-16 10-32 12-52 6Z" fill="#7eaea4"/><path d="M84 148c-20-2-36 2-50 12 18 6 34 4 50-2Z" fill="#5f8f84"/><path d="M90 128c16-10 30-12 46-6-14 14-28 18-46 14Z" fill="#8fbfb4"/><path d="M88 108c-16-8-30-8-44 0 16 10 30 10 44 2Z" fill="#4f7f74"/><path d="M94 88c14-12 26-16 40-12-12 16-24 22-40 18Z" fill="#6f9e94"/><path d="M92 70c-12-10-24-12-36-4 12 12 24 14 36 6Z" fill="#3f6f64"/><path d="M98 52c10-14 18-22 28-24-6 16-14 26-28 32Z" fill="#8fbfb4"/><path d="M96 48c-8-12-16-18-26-16 4 14 12 22 26 26Z" fill="#5f8f84"/></svg>
    @break
  @case('trust-shield')
    <svg viewBox="0 0 24 24"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
    @break
  @case('trust-mill')
    <svg viewBox="0 0 24 24"><path d="M3 21h18M5 21V10l7-5 7 5v11"/></svg>
    @break
  @case('trust-clock')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
    @break
  @case('trust-export')
    <svg viewBox="0 0 24 24"><path d="M4 19h16M6 19V9l6-5 6 5v10"/></svg>
    @break
  @case('step-fiber')
    <svg viewBox="0 0 24 24"><path d="M5 10h14v8H5z"/><path d="M5 14h14M9 10v8M15 10v8"/><path d="M8 10V8.2C8 6.4 9.6 5 12 5s4 1.4 4 3.2V10"/></svg>
    @break
  @case('step-spin')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3M5.2 5.2l2.1 2.1M16.7 16.7l2.1 2.1M18.8 5.2l-2.1 2.1M7.3 16.7l-2.1 2.1"/></svg>
    @break
  @case('step-weave')
    <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18M6 3v18M12 3v18M18 3v18"/></svg>
    @break
  @case('step-dye')
    <svg viewBox="0 0 24 24"><path d="M12 3.2c2.6 3.4 4.8 6.1 4.8 8.8a4.8 4.8 0 0 1-9.6 0c0-2.7 2.2-5.4 4.8-8.8z"/></svg>
    @break
  @case('step-finish')
    <svg viewBox="0 0 24 24"><path d="M4 8l8-3 8 3-8 3-8-3z"/><path d="M4 12l8 3 8-3"/><path d="M4 16l8 3 8-3"/></svg>
    @break
  @case('step-dispatch')
    <svg viewBox="0 0 24 24"><path d="M3 8h13v9H3z"/><path d="M16 11h3.2L21 13.5V17h-5"/><circle cx="7" cy="17.5" r="1.4"/><circle cx="17.2" cy="17.5" r="1.4"/></svg>
    @break
  @case('cert-lab')
    <svg viewBox="0 0 24 24"><path d="M9 3h6l1 3h3v13H5V6h3l1-3z"/><path d="M9 13h6M12 10v6"/></svg>
    @break
  @case('cert-process')
    <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/><path d="M8 3v18M16 3v18"/></svg>
    @break
  @case('cert-doc')
    <svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7z"/><path d="M15 3v5h5"/><path d="M10 13h6M10 17h4"/></svg>
    @break
  @case('cert-shield')
    <svg viewBox="0 0 24 24"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>
    @break
  @case('phone')
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6.5 4.5h3l1.2 3-1.8 1.1a12 12 0 006.5 6.5l1.1-1.8 3 1.2v3A1.5 1.5 0 0018 19 14.5 14.5 0 015 6a1.5 1.5 0 001.5-1.5z"/></svg>
    @break
  @case('envelope')
    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="5.5" width="17" height="13" rx="1.5"/><path d="M4 7l8 6 8-6"/></svg>
    @break
  @case('vmv-vision')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M2 12h3M19 12h3M12 2v3M12 19v3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/></svg>
    @break
  @case('vmv-mission')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2"/></svg>
    @break
  @case('vmv-values')
    <svg viewBox="0 0 24 24"><path d="M8 11l2.5 2.5L16 8"/><path d="M7 4h10l3 4v12H4V8l3-4z"/></svg>
    @break
  @case('time-found')
    <svg viewBox="0 0 24 24"><path d="M4 19h16M6 19V9l6-5 6 5v10"/></svg>
    @break
  @case('time-spin')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2"/></svg>
    @break
  @case('time-loom')
    <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18M7 3v18M17 3v18"/></svg>
    @break
  @case('time-dye')
    <svg viewBox="0 0 24 24"><path d="M12 3.2c2.6 3.4 4.8 6.1 4.8 8.8a4.8 4.8 0 0 1-9.6 0c0-2.7 2.2-5.4 4.8-8.8z"/></svg>
    @break
  @case('time-globe')
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/></svg>
    @break
  @case('wish')
    <svg viewBox="0 0 24 24" width="15" height="15" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.45"><path d="M12 19.4s-6.2-3.9-6.2-8A3.6 3.6 0 0 1 12 8.2a3.6 3.6 0 0 1 6.2 3.2c0 4.1-6.2 8-6.2 8z"/></svg>
    @break
  @case('swatch')
    <svg viewBox="0 0 16 16" width="13" height="13" aria-hidden="true"><rect x="1.15" y="1.15" width="5.7" height="5.7" rx="1.15" fill="currentColor"/><rect x="9.15" y="1.15" width="5.7" height="5.7" rx="1.15" fill="currentColor"/><rect x="1.15" y="9.15" width="5.7" height="5.7" rx="1.15" fill="currentColor"/><rect x="9.15" y="9.15" width="5.7" height="5.7" rx="1.15" fill="currentColor"/></svg>
    @break
  @case('view-grid')
    <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true"><rect x="1" y="1" width="6" height="6" rx="1.1" fill="currentColor"/><rect x="9" y="1" width="6" height="6" rx="1.1" fill="currentColor"/><rect x="1" y="9" width="6" height="6" rx="1.1" fill="currentColor"/><rect x="9" y="9" width="6" height="6" rx="1.1" fill="currentColor"/></svg>
    @break
  @case('view-list')
    <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M1 3.2h14v1.3H1zm0 4.15h14v1.3H1zm0 4.15h14v1.3H1z"/></svg>
    @break
  @case('calendar')
    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="1.5"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
    @break
  @case('map-pin')
    <svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-10a7 7 0 10-14 0c0 4.8 7 10 7 10z"/><circle cx="12" cy="11" r="2.4"/></svg>
    @break
  @case('ext')
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5h5v5"/><path d="M19 5l-9 9"/><path d="M17 13.5V19a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h5.5"/></svg>
    @break
@endswitch
