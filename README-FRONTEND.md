# Islam Textile — Frontend handoff (Prompt 2 rebuild)

Static HTML/CSS/JS site for a Bangladesh cotton & woven textile manufacturer  
(**Yarn → Woven fabric → Dyeing & finishing**). Theme colors are CSS-variable driven for Laravel admin later.

## How to open

1. Open [`public-site/index.html`](public-site/index.html) in a browser (Live Server recommended).
2. Admin shells: [`admin-static/login.html`](../admin-static/login.html) (unchanged demo).

No build step. Google Fonts: **Montserrat** + **Cormorant Garamond**.

## Brand / theme

- Defaults in [`assets/css/tokens.css`](public-site/assets/css/tokens.css): `--color-primary`, `--color-accent`, surfaces, fonts
- Override hook: [`assets/css/theme-override.css`](public-site/assets/css/theme-override.css) (empty; Laravel can inject `:root` values)
- Stylesheets: `main.css` → imports `tokens.css`, `components.css`, `pages.css`
- Tagline: Weaving Tradition, Ensuring Quality
- Locale: **Bangladesh** (Dhaka office, Narayanganj mill, `+880…`)

## Primary navigation (Facilities chrome)

HOME · ABOUT US · PRODUCTS (dropdown) · FACILITIES & CAPACITY · QUALITY · SUSTAINABILITY · CONTACT US  

Footer also links Process, Gallery, News, Careers, Privacy, Terms.

## JavaScript (`assets/js/main.js`)

- Sticky / transparent header, mobile nav, products dropdown
- Stats counters (`data-count`)
- Scroll reveal
- Gallery filters, sort, load-more, lightbox next/prev
- News category / year / sort / pagination / search overlay
- Product filter accordions, sort, grid/list, wishlist
- Product detail thumbnail gallery
- Form validation + file drag-drop (demo only)
- Careers resume modal
- Privacy scrollspy, social share, newsletter demo

## Theme → CMS map (Prompt 3)

| CSS variable | Suggested setting |
|--------------|-------------------|
| `--color-primary` | `settings.theme_primary` |
| `--color-primary-deep` | `settings.theme_primary_deep` |
| `--color-accent` | `settings.theme_accent` |
| `--color-surface` | `settings.theme_surface` |

See also [`docs/CMS_FIELD_MAP.md`](docs/CMS_FIELD_MAP.md).

## Images

Mill/fabric photos curated from `imgs/` into `assets/images/{hero,gallery,products,news,facilities,process}`. **No face / portrait imagery.** Leadership uses initials.

## Placeholders

- Phone `+880 1XXX-XXXXXX`, email `info@islamtextile.com`
- Dhaka office / Narayanganj factory addresses in brackets where TBD
- Forms redirect or alert (no backend yet)
