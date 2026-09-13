(() => {
  const qs = (s, el = document) => el.querySelector(s);
  const qsa = (s, el = document) => [...el.querySelectorAll(s)];

  /* Sticky header */
  const header = qs(".site-header");
  if (header) {
    const onScroll = () => {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
      if (header.classList.contains("site-header--transparent")) {
        header.classList.toggle("is-over-hero", window.scrollY < 40);
      }
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* Mobile nav */
  const toggle = qs(".nav-toggle");
  const mobileNav = qs(".mobile-nav");
  if (toggle && mobileNav) {
    const setOpen = (open) => {
      mobileNav.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    };
    toggle.addEventListener("click", () => setOpen(!mobileNav.classList.contains("is-open")));
    mobileNav.querySelectorAll("a").forEach((a) => a.addEventListener("click", () => setOpen(false)));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") setOpen(false);
    });
  }

  /* Reveal */
  const reveals = qsa(".reveal");
  if (reveals.length && "IntersectionObserver" in window) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add("is-visible"));
  }

  /* Counters — numeric data-count only (product cards reuse data-count for yarn count) */
  const counters = qsa("[data-count]").filter((el) => {
    const raw = el.getAttribute("data-count");
    return raw !== null && raw !== "" && !Number.isNaN(Number(raw));
  });
  if (counters.length && "IntersectionObserver" in window) {
    const animate = (el) => {
      const target = Number(el.getAttribute("data-count") || "0");
      const suffix = el.getAttribute("data-suffix") || "";
      const duration = 1200;
      const start = performance.now();
      const tick = (now) => {
        const p = Math.min(1, (now - start) / duration);
        const eased = 0.2 + 0.8 * p;
        el.textContent = Math.floor(target * eased).toLocaleString() + suffix;
        if (p < 1) requestAnimationFrame(tick);
        else el.textContent = target.toLocaleString() + suffix;
      };
      requestAnimationFrame(tick);
    };
    const cio = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animate(entry.target);
            cio.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.35 }
    );
    counters.forEach((el) => cio.observe(el));
  }

  /* Gallery albums, sort, and load more */
  const galleryRoot = qs("[data-gallery]");
  if (galleryRoot) {
    const grid = qs("[data-gallery-grid]", galleryRoot);
    const buttons = qsa("[data-filter]", galleryRoot);
    const status = qs("[data-filter-status]", galleryRoot);
    const sortEl = qs("[data-gallery-sort]", galleryRoot);
    const more = qs("[data-load-more]", galleryRoot);
    const pageSize = Number(more?.getAttribute("data-page-size") || "8");
    let cat = "all";
    let shown = pageSize;

    const renderGallery = () => {
      if (!grid) return;
      let matched = 0;
      let visible = 0;
      qsa("[data-load-item]", grid).forEach((el) => {
        const match = cat === "all" || el.getAttribute("data-category") === cat;
        el.classList.remove("is-feature");
        if (!match) {
          el.classList.add("is-off");
          el.classList.remove("is-deferred");
          return;
        }
        el.classList.remove("is-off");
        if (visible < shown) {
          el.classList.remove("is-deferred");
          if (visible === 0) el.classList.add("is-feature");
          visible += 1;
        } else {
          el.classList.add("is-deferred");
        }
        matched += 1;
      });
      if (status) {
        status.textContent = cat === "all"
          ? `Showing all photos (${matched})`
          : `Showing ${matched} photo${matched === 1 ? "" : "s"}`;
      }
      if (more) more.hidden = shown >= matched;
    };

    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        cat = btn.getAttribute("data-filter") || "all";
        buttons.forEach((b) => b.classList.toggle("is-active", b === btn));
        shown = pageSize;
        renderGallery();
      });
    });

    sortEl?.addEventListener("change", () => {
      if (!grid) return;
      const items = qsa("[data-load-item]", grid);
      items.sort((a, b) => {
        const da = a.getAttribute("data-date") || "";
        const db = b.getAttribute("data-date") || "";
        return sortEl.value === "oldest" ? da.localeCompare(db) : db.localeCompare(da);
      });
      items.forEach((el) => grid.appendChild(el));
      renderGallery();
    });

    more?.addEventListener("click", () => {
      shown += pageSize;
      renderGallery();
    });

    renderGallery();
  }

  /* Lightbox with next/prev */
  const lightbox = qs(".lightbox");
  const lightboxImg = lightbox && qs("img", lightbox);
  const lightboxClose = lightbox && qs(".lightbox__close", lightbox);
  let lbSources = [];
  let lbAlts = [];
  let lbIndex = 0;
  const openLightbox = (src, sources, alts) => {
    if (!lightbox || !lightboxImg || !src) return;
    lbSources = sources && sources.length ? sources : [src];
    lbAlts = alts && alts.length ? alts : [];
    lbIndex = Math.max(0, lbSources.indexOf(src));
    lightboxImg.src = lbSources[lbIndex];
    lightboxImg.alt = lbAlts[lbIndex] || "";
    lightbox.classList.add("is-open");
    lightboxClose && lightboxClose.focus();
  };
  const closeLightbox = () => lightbox && lightbox.classList.remove("is-open");
  const stepLightbox = (dir) => {
    if (!lbSources.length) return;
    lbIndex = (lbIndex + dir + lbSources.length) % lbSources.length;
    lightboxImg.src = lbSources[lbIndex];
    lightboxImg.alt = lbAlts[lbIndex] || "";
  };
  qsa("[data-lightbox]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const all = qsa("[data-lightbox]").filter((b) => !b.classList.contains("is-off") && !b.classList.contains("is-deferred") && !b.hidden && b.offsetParent !== null);
      const sources = all.map((b) => b.getAttribute("data-lightbox")).filter(Boolean);
      const alts = all.map((b) => qs("img", b)?.alt || "");
      openLightbox(btn.getAttribute("data-lightbox"), sources, alts);
    });
  });
  lightboxClose && lightboxClose.addEventListener("click", closeLightbox);
  lightbox &&
    lightbox.addEventListener("click", (e) => {
      if (e.target === lightbox) closeLightbox();
    });
  const prevBtn = qs(".lightbox__nav--prev");
  const nextBtn = qs(".lightbox__nav--next");
  prevBtn && prevBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    stepLightbox(-1);
  });
  nextBtn && nextBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    stepLightbox(1);
  });
  document.addEventListener("keydown", (e) => {
    if (!lightbox || !lightbox.classList.contains("is-open")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowLeft") stepLightbox(-1);
    if (e.key === "ArrowRight") stepLightbox(1);
  });

  /* News: category, year, sort, pagination, search */
  const newsRoot = qs("[data-news]");
  if (newsRoot) {
    const cards = qsa("[data-news-card]", newsRoot);
    const pageSize = Number(newsRoot.getAttribute("data-page-size") || "9");
    let page = 1;
    let category = "all";
    let year = "all";
    let sort = "newest";
    let query = "";

    const filtered = () => {
      let list = cards.filter((c) => {
        const catOk = category === "all" || c.getAttribute("data-category") === category;
        const yearOk = year === "all" || c.getAttribute("data-year") === year;
        const hay = (c.getAttribute("data-search") || c.textContent || "").toLowerCase();
        const qOk = !query || hay.includes(query);
        return catOk && yearOk && qOk;
      });
      list.sort((a, b) => {
        const da = a.getAttribute("data-date") || "";
        const db = b.getAttribute("data-date") || "";
        return sort === "oldest" ? da.localeCompare(db) : db.localeCompare(da);
      });
      return list;
    };

    const render = () => {
      const list = filtered();
      const total = list.length;
      const pages = Math.max(1, Math.ceil(total / pageSize));
      if (page > pages) page = pages;
      const start = (page - 1) * pageSize;
      const slice = list.slice(start, start + pageSize);
      cards.forEach((c) => {
        c.hidden = true;
      });
      slice.forEach((c) => {
        c.hidden = false;
      });
      const status = qs("[data-news-status]", newsRoot);
      if (status) {
        const from = total ? start + 1 : 0;
        const to = Math.min(start + pageSize, total);
        status.textContent = `Showing ${from}–${to} of ${total} results`;
      }
      const pag = qs("[data-pagination]", newsRoot);
      if (pag) {
        pag.innerHTML = "";
        const addBtn = (label, p, active) => {
          const b = document.createElement("button");
          b.type = "button";
          b.textContent = label;
          if (active) b.classList.add("is-active");
          b.addEventListener("click", () => {
            page = p;
            render();
          });
          pag.appendChild(b);
        };
        addBtn("‹", Math.max(1, page - 1));
        for (let i = 1; i <= pages; i++) addBtn(String(i), i, i === page);
        addBtn("›", Math.min(pages, page + 1));
      }
    };

    qsa("[data-news-cat]").forEach((btn) => {
      btn.addEventListener("click", () => {
        category = btn.getAttribute("data-news-cat") || "all";
        qsa("[data-news-cat]").forEach((b) => b.classList.toggle("is-active", b === btn));
        page = 1;
        render();
      });
    });
    const yearSel = qs("[data-news-year]");
    yearSel &&
      yearSel.addEventListener("change", () => {
        year = yearSel.value;
        page = 1;
        render();
      });
    const sortSel = qs("[data-news-sort]");
    sortSel &&
      sortSel.addEventListener("change", () => {
        sort = sortSel.value;
        render();
      });
    const queryInput = qs("[data-news-query]", newsRoot);
    queryInput &&
      queryInput.addEventListener("input", () => {
        query = queryInput.value.trim().toLowerCase();
        page = 1;
        render();
      });
    render();

    /* Search overlay */
    const overlay = qs(".search-overlay");
    const openSearch = qsa("[data-search-open]");
    const closeSearch = qs(".search-overlay__close");
    const searchInput = overlay && qs("input", overlay);
    openSearch.forEach((btn) =>
      btn.addEventListener("click", () => {
        overlay && overlay.classList.add("is-open");
        searchInput && searchInput.focus();
      })
    );
    closeSearch &&
      closeSearch.addEventListener("click", () => overlay && overlay.classList.remove("is-open"));
    searchInput &&
      searchInput.addEventListener("input", () => {
        query = searchInput.value.trim().toLowerCase();
        page = 1;
        render();
      });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && overlay) overlay.classList.remove("is-open");
    });
  }

  /* Product filters accordion + checkboxes */
  qsa(".filter-accordion__btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const acc = btn.closest(".filter-accordion");
      acc?.classList.toggle("is-open");
      btn.setAttribute("aria-expanded", acc?.classList.contains("is-open") ? "true" : "false");
    });
  });
  /* Open first product filter group by default */
  const firstAcc = qs(".filters-panel .filter-accordion");
  if (firstAcc) firstAcc.classList.add("is-open");
  const productGrid = qs("[data-product-grid]");
  if (productGrid) {
    const cards = qsa("[data-product]", productGrid);
    const checks = qsa("[data-filter-key]");
    const sortSel = qs("[data-product-sort]");
    const status = qs("[data-product-status]");
    const empty = qs("[data-product-empty]");
    qsa("[data-filter-count]").forEach((el) => {
      const key = el.getAttribute("data-filter-count");
      const val = el.getAttribute("data-filter-value");
      el.textContent = String(cards.filter((card) => card.getAttribute(`data-${key}`) === val).length);
    });

    const apply = () => {
      const groups = {};
      checks.forEach((c) => {
        if (!c.checked) return;
        const key = c.getAttribute("data-filter-key");
        const val = c.value;
        (groups[key] || (groups[key] = [])).push(val);
      });
      let list = cards.filter((card) =>
        Object.keys(groups).every((key) => {
          const attr = card.getAttribute(`data-${key}`) || "";
          return groups[key].includes(attr);
        })
      );
      if (sortSel) {
        list = [...list].sort((a, b) => {
          const da = a.getAttribute("data-date") || "";
          const db = b.getAttribute("data-date") || "";
          if (sortSel.value === "oldest") return da.localeCompare(db);
          if (sortSel.value === "name") return (a.getAttribute("data-name") || "").localeCompare(b.getAttribute("data-name") || "");
          return db.localeCompare(da);
        });
        list.forEach((c) => productGrid.appendChild(c));
      }
      cards.forEach((c) => {
        c.hidden = !list.includes(c);
      });
      if (status) {
        status.textContent = list.length
          ? `Showing 1–${list.length} of ${cards.length} products`
          : `Showing 0 of ${cards.length} products`;
      }
      if (empty) empty.hidden = list.length !== 0;
    };

    checks.forEach((c) => c.addEventListener("change", apply));
    sortSel && sortSel.addEventListener("change", apply);
    qsa("[data-clear-filters]").forEach((btn) =>
      btn.addEventListener("click", () => {
        checks.forEach((c) => {
          c.checked = false;
        });
        apply();
      })
    );
    apply();

    qsa("[data-view]").forEach((btn) => {
      btn.addEventListener("click", () => {
        qsa("[data-view]").forEach((b) => b.classList.remove("is-active"));
        btn.classList.add("is-active");
        productGrid.classList.toggle("is-list", btn.getAttribute("data-view") === "list");
      });
    });
  }

  /* Wishlist */
  qsa("[data-wish]").forEach((btn) => {
    btn.addEventListener("click", () => {
      btn.classList.toggle("is-active");
      btn.setAttribute("aria-pressed", btn.classList.contains("is-active") ? "true" : "false");
    });
  });

  /* Product detail gallery */
  const mainImg = qs("[data-gallery-main]");
  if (mainImg) {
    const thumbs = qsa("[data-gallery-thumb]");
    thumbs.forEach((thumb) => {
      thumb.addEventListener("click", () => {
        const src = thumb.getAttribute("data-gallery-thumb");
        if (src) mainImg.src = src;
        thumbs.forEach((t) => t.classList.toggle("is-active", t === thumb));
      });
    });
    const track = qs(".pdp-thumbs__track");
    qs("[data-thumbs-prev]")?.addEventListener("click", () => track && (track.scrollLeft -= 100));
    qs("[data-thumbs-next]")?.addEventListener("click", () => track && (track.scrollLeft += 100));
  }

  /* File drop */
  qsa(".file-drop").forEach((drop) => {
    const input = qs('input[type="file"]', drop);
    const nameEl = qs(".file-drop__name", drop);
    const accept = (input?.accept || "").split(",").map((s) => s.trim().toLowerCase()).filter(Boolean);
    const max = Number(drop.getAttribute("data-max-mb") || "10") * 1024 * 1024;
    const show = (file) => {
      if (!file) return;
      if (file.size > max) {
        alert(`File must be ${drop.getAttribute("data-max-mb") || 10}MB or less.`);
        return;
      }
      if (accept.length) {
        const ok = accept.some((a) => file.name.toLowerCase().endsWith(a.replace(".", "")) || file.type.includes(a.replace(".", "")));
        if (!ok) {
          alert("Unsupported file type.");
          return;
        }
      }
      if (nameEl) nameEl.textContent = file.name;
    };
    drop.addEventListener("click", () => input?.click());
    input?.addEventListener("change", () => show(input.files?.[0]));
    ["dragenter", "dragover"].forEach((ev) =>
      drop.addEventListener(ev, (e) => {
        e.preventDefault();
        drop.classList.add("is-dragover");
      })
    );
    ["dragleave", "drop"].forEach((ev) =>
      drop.addEventListener(ev, (e) => {
        e.preventDefault();
        drop.classList.remove("is-dragover");
      })
    );
    drop.addEventListener("drop", (e) => show(e.dataTransfer?.files?.[0]));
  });

  /* Forms */
  qsa("form[data-validate]").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      let valid = true;
      form.querySelectorAll("[required]").forEach((field) => {
        const wrap = field.closest(".field");
        const ok = String(field.value || "").trim().length > 0;
        wrap?.classList.toggle("is-invalid", !ok);
        if (!ok) valid = false;
        if (field.type === "email" && ok) {
          const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
          wrap?.classList.toggle("is-invalid", !emailOk);
          if (!emailOk) valid = false;
        }
      });
      if (!valid) return;
      if (form.getAttribute("action") && form.getAttribute("action") !== "#") {
        form.submit();
        return;
      }
      const success = form.getAttribute("data-success");
      if (success) window.location.href = success;
    });
  });

  /* Modals */
  qsa("[data-modal-open]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-modal-open");
      const modal = id && document.getElementById(id);
      modal?.classList.add("is-open");
    });
  });
  qsa(".modal").forEach((modal) => {
    qs(".modal__close", modal)?.addEventListener("click", () => modal.classList.remove("is-open"));
    modal.addEventListener("click", (e) => {
      if (e.target === modal) modal.classList.remove("is-open");
    });
  });

  /* Privacy scrollspy */
  const spyLinks = qsa("[data-scrollspy] a");
  if (spyLinks.length && "IntersectionObserver" in window) {
    const map = new Map();
    spyLinks.forEach((a) => {
      const id = a.getAttribute("href")?.slice(1);
      const section = id && document.getElementById(id);
      if (section) map.set(section, a);
    });
    const sio = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            spyLinks.forEach((l) => l.classList.remove("is-active"));
            map.get(entry.target)?.classList.add("is-active");
          }
        });
      },
      { rootMargin: "-30% 0px -55% 0px", threshold: 0 }
    );
    map.forEach((_, section) => sio.observe(section));
  }

  /* Social share */
  qsa("[data-share]").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const type = btn.getAttribute("data-share");
      const url = encodeURIComponent(window.location.href);
      const title = encodeURIComponent(document.title);
      if (type === "native" && navigator.share) {
        try {
          await navigator.share({ title: document.title, url: window.location.href });
        } catch (_) {}
        return;
      }
      const urls = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
        email: `mailto:?subject=${title}&body=${url}`,
      };
      if (urls[type]) window.open(urls[type], "_blank", "noopener,width=600,height=500");
    });
  });

  /* Contact map pins — only when admin supplies a location */
  const contactMap = qs(".contact-map[data-cms-field='contact-map']");
  if (contactMap) {
    const pinToView = (input) => {
      const raw = (input || "").trim();
      if (!raw || /^\s*javascript:/i.test(raw)) return null;

      const coord = raw.match(/^(-?\d{1,2}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)$/);
      if (coord) {
        const lat = Number(coord[1]);
        const lng = Number(coord[2]);
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return null;
        const q = `${lat},${lng}`;
        return {
          embed: `https://maps.google.com/maps?q=${q}&z=15&output=embed`,
          open: `https://www.google.com/maps/search/?api=1&query=${q}`,
        };
      }

      let url;
      try {
        url = new URL(raw);
      } catch (_) {
        const q = encodeURIComponent(raw);
        return {
          embed: `https://maps.google.com/maps?q=${q}&z=14&output=embed`,
          open: `https://www.google.com/maps/search/?api=1&query=${q}`,
        };
      }

      if (url.protocol !== "https:") return null;
      const host = url.hostname.replace(/^www\./, "");
      const allowed =
        host === "google.com" ||
        host.endsWith(".google.com") ||
        host === "maps.app.goo.gl" ||
        host === "goo.gl";
      if (!allowed) return null;

      if (url.pathname.includes("/maps/embed") || url.searchParams.get("output") === "embed") {
        const open = new URL(url.toString());
        open.searchParams.delete("output");
        return { embed: url.toString(), open: open.toString() };
      }

      const at = url.href.match(/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/);
      if (at) {
        const q = `${at[1]},${at[2]}`;
        return {
          embed: `https://maps.google.com/maps?q=${q}&z=15&output=embed`,
          open: `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(q)}`,
        };
      }

      const query = url.searchParams.get("q") || url.searchParams.get("query");
      const place = url.pathname.match(/\/maps\/(?:place|search)\/([^/@]+)/);
      const label = query || (place ? decodeURIComponent(place[1].replace(/\+/g, " ")) : "");
      if (label) {
        const q = encodeURIComponent(label);
        return {
          embed: `https://maps.google.com/maps?q=${q}&z=15&output=embed`,
          open: `https://www.google.com/maps/search/?api=1&query=${q}`,
        };
      }

      return { embed: "", open: url.toString() };
    };

    const params = new URLSearchParams(window.location.search);
    let office = contactMap.getAttribute("data-office-pin") || "";
    let mill = contactMap.getAttribute("data-mill-pin") || "";
    if (!office && !mill) {
      office = params.get("officePin") || "";
      mill = params.get("millPin") || "";
    }
    if (!office && !mill) {
      try {
        const stored = JSON.parse(localStorage.getItem("it-contact-pins") || "{}");
        office = stored.office || "";
        mill = stored.mill || "";
      } catch (_) {}
    }

    const pins = [
      office && { id: "office", label: "Office", view: pinToView(office) },
      mill && { id: "mill", label: "Mill", view: pinToView(mill) },
    ].filter((pin) => pin && pin.view && (pin.view.embed || pin.view.open));

    if (pins.length) {
      const frame = qs("[data-map-frame]", contactMap);
      const canvas = qs("[data-map-canvas]", contactMap);
      const open = qs("[data-map-open]", contactMap);
      const note = qs("[data-map-note]", contactMap);
      const switcher = qs("[data-map-switch]", contactMap);

      const show = (pin) => {
        if (!pin) return;
        if (frame && canvas && pin.view.embed) {
          frame.src = pin.view.embed;
          frame.title = pin.label + " pin";
          canvas.hidden = false;
          contactMap.classList.add("is-pinned");
        } else {
          if (frame) frame.removeAttribute("src");
          if (canvas) canvas.hidden = true;
          contactMap.classList.remove("is-pinned");
        }
        if (open) open.href = pin.view.open || pin.view.embed;
        if (note) note.textContent = "Opens the " + pin.label.toLowerCase() + " pin in Google Maps.";
        qsa("button", switcher).forEach((btn) => {
          const on = btn.getAttribute("data-pin") === pin.id;
          btn.classList.toggle("is-active", on);
          btn.setAttribute("aria-selected", on ? "true" : "false");
        });
      };

      if (pins.length > 1 && switcher) {
        switcher.classList.add("is-on");
        pins.forEach((pin) => {
          const btn = document.createElement("button");
          btn.type = "button";
          btn.setAttribute("role", "tab");
          btn.setAttribute("data-pin", pin.id);
          btn.textContent = pin.label;
          btn.addEventListener("click", () => show(pin));
          switcher.appendChild(btn);
        });
      }

      show(pins[0]);
    }
  }

  /* Newsletter */
  qsa("[data-newsletter]").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = qs('input[type="email"]', form);
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        alert("Please enter a valid email.");
        return;
      }
      if (form.getAttribute("action") && form.getAttribute("action") !== "#") {
        form.submit();
        return;
      }
    });
  });
})();
