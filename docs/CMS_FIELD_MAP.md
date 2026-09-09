# CMS field map (for Prompt 3 Laravel)

Attributes use `data-cms-field="..."` in HTML. Map these to admin-editable models.

## Global / settings
| Field | Suggested model |
|-------|-----------------|
| `site-logo` | SiteSetting.logo_path (chosen in admin logo picker) |
| `footer-blurb` | SiteSetting.footer_blurb |
| Contact block on contact page | SiteSetting phones, emails, addresses |
| SEO defaults | SeoSetting |
| **Theme colors** | `settings.theme_primary` → `--color-primary`; `theme_primary_deep` → `--color-primary-deep`; `theme_accent` → `--color-accent`; `theme_surface` → `--color-surface` (inject via Blade into `:root` or `theme-override.css`) |

## Home
| Field | Notes |
|-------|-------|
| `hero`, `hero-headline`, `hero-lead` | Home sections / Banner |
| Stats `data-count` values | Stat model |
| Product category teasers | ProductCategory featured |
| `cta-band` | Home CTA section |

## About / Process / Facilities / Sustainability / Quality
| Field | Notes |
|-------|-------|
| `page-title`, `page-lead` | Page model |
| Section bodies | Page blocks or dedicated models (ProcessStep, Facility, Certificate) |

## Products
| Field | Notes |
|-------|-------|
| Category cards | ProductCategory |
| Product grid / filters | Product |
| Specs table | Product.specifications JSON |
| Product SEO | Product meta_* |

## Gallery
| Field | Notes |
|-------|-------|
| Filters + items | GalleryAlbum + Media |
| Alt / captions | Media.alt, Media.caption |

## News
| Field | Notes |
|-------|-------|
| `page-title` | News index |
| `article-title`, `article-body` | Post |
| Cover images | Media |

## Careers
| Field | Notes |
|-------|-------|
| `jobs-list`, `job-title`, `job-description` | Job |
| `job-apply-form` | JobApplication (+ CV upload) |

## Contact
| Field | Notes |
|-------|-------|
| `contact-info` | SiteSetting |
| `quote-form` | Inquiry (name, company, email, phone, country, interest, message) |

## Legal
| Field | Notes |
|-------|-------|
| `privacy-body`, `terms-body` | Page |

## Admin modules implied
Pages, Banners, ProductCategories, Products, ProcessSteps, Facilities, Stats, Certificates, Gallery/Media, News, Jobs/Applications, Inquiries, SiteSettings (incl. **logo picker**), SEO.
