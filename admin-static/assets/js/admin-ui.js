/* Panel behavior: forms, lists, theme, media pick. Does not write public HTML. */
(function () {
  var store = window.ITAdmin;
  if (!store) return;

  var dirty = false;

  function esc(value) {
    return String(value == null ? "" : value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/"/g, "&quot;");
  }

  var ICONS = {
    plus: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>',
    edit: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10.5-10.5a1.8 1.8 0 0 0-2.5-2.5L5.5 17.5 4 20z"/><path d="m13.5 6.5 4 4"/></svg>',
    trash: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14"/><path d="M9 7V5h6v2"/><path d="M8 7l.8 12h6.4L16 7"/></svg>',
    view: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6z"/><circle cx="12" cy="12" r="2.4"/></svg>',
    back: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6 9 12l6 6"/></svg>',
    image: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="14" rx="2"/><circle cx="9" cy="10" r="1.3"/><path d="m7 16 3.2-3.2 2.3 2.2L15 12l3 4"/></svg>',
    up: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 14 6-6 6 6"/></svg>',
    down: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 10 6 6 6-6"/></svg>',
    close: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 7 10 10M17 7 7 17"/></svg>',
    check: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>'
  };

  function iconBtn(kind, label, extra) {
    extra = extra || "";
    var href = extra.match(/href="([^"]*)"/);
    var tag = href ? "a" : "button";
    var type = href ? "" : " type=\"button\"";
    return "<" + tag + type + " class=\"icon-btn icon-btn--" + kind + "\" title=\"" + esc(label) + "\" aria-label=\"" + esc(label) + "\" " + extra + ">" + (ICONS[kind] || "") + "</" + tag + ">";
  }

  function actionsHtml(parts) {
    return '<div class="row-actions">' + parts.join("") + "</div>";
  }

  function thumb(path, alt) {
    if (!path) return "";
    return '<img class="list-thumb" src="' + esc(publicSrc(path)) + '" alt="' + esc(alt || "") + '">';
  }

  function publicSrc(path) {
    if (!path) return "";
    if (/^(https?:|data:|\.\.\/)/.test(path)) return path;
    if (path.indexOf("assets/images/brand/") === 0) return path;
    if (path.indexOf("assets/") === 0) return "../public-site/" + path;
    return "../public-site/" + path.replace(/^\//, "");
  }

  function toast(message) {
    var node = document.createElement("div");
    node.className = "toast";
    node.setAttribute("role", "status");
    node.textContent = message;
    document.body.appendChild(node);
    setTimeout(function () { node.remove(); }, 2400);
  }

  function getPath(obj, path) {
    return path.split(".").reduce(function (cur, key) {
      return cur == null ? undefined : cur[key];
    }, obj);
  }

  function setPath(obj, path, value) {
    var parts = path.split(".");
    var cur = obj;
    parts.forEach(function (key, index) {
      if (index === parts.length - 1) cur[key] = value;
      else {
        if (!cur[key] || typeof cur[key] !== "object") cur[key] = {};
        cur = cur[key];
      }
    });
  }

  function applyTheme(theme) {
    if (!theme) return;
    var root = document.documentElement.style;
    root.setProperty("--forest", theme.primary);
    root.setProperty("--teal", theme.accent);
    root.setProperty("--surface", theme.surface);
    root.setProperty("--sidebar", theme.deep);
    root.setProperty("--sidebar-panel", theme.deep);
    root.setProperty("--sidebar-active", theme.primary);
    var preview = document.querySelector("[data-theme-preview]");
    if (!preview) return;
    preview.querySelector(".swatch-btn").style.background = theme.primary;
    preview.querySelector(".swatch-link").style.color = theme.accent;
    preview.querySelector(".swatch-band").style.background = theme.deep;
  }

  function newCount() {
    return (store.get("inquiries") || []).filter(function (row) { return row.status === "New"; }).length;
  }

  function updateBadge() {
    var count = newCount();
    document.querySelectorAll(".admin-nav__badge").forEach(function (badge) {
      badge.textContent = String(count);
      badge.hidden = count === 0;
    });
    document.querySelectorAll(".admin-nav__bell").forEach(function (bell) {
      bell.href = "inquiries.html";
      bell.setAttribute("aria-label", count + " new inquiries");
    });
  }

  function markDirty(form) {
    dirty = true;
    var note = form.querySelector("[data-unsaved]");
    if (note) note.hidden = false;
  }

  function clearDirty(form) {
    dirty = false;
    var note = form.querySelector("[data-unsaved]");
    if (note) note.hidden = true;
  }

  function readBound(root) {
    var data = {};
    root.querySelectorAll("[name]").forEach(function (field) {
      if (field.closest("[data-repeater]")) return;
      if (field.type === "radio" && !field.checked) return;
      var value = field.type === "checkbox" ? field.checked : field.value;
      setPath(data, field.name, value);
    });
    root.querySelectorAll("[data-repeater]").forEach(function (box) {
      var rows = [];
      box.querySelectorAll("[data-row]").forEach(function (row) {
        var item = {};
        var empty = true;
        row.querySelectorAll("[data-key]").forEach(function (field) {
          item[field.getAttribute("data-key")] = field.value;
          if (String(field.value || "").trim()) empty = false;
        });
        if (!empty) rows.push(item);
      });
      setPath(data, box.getAttribute("data-repeater"), rows);
    });
    return data;
  }

  function writeBound(root, data) {
    root.querySelectorAll("[name]").forEach(function (field) {
      if (field.closest("[data-repeater]")) return;
      var value = getPath(data, field.name);
      if (value == null) return;
      if (field.type === "checkbox") field.checked = !!value;
      else if (field.type === "radio") field.checked = field.value === String(value);
      else field.value = value;
    });
    root.querySelectorAll("[data-repeater]").forEach(function (box) {
      renderRepeater(box, getPath(data, box.getAttribute("data-repeater")) || []);
    });
    root.querySelectorAll("[data-image-field]").forEach(syncImagePreview);
    root.querySelectorAll("[data-hex]").forEach(syncColor);
  }

  function fieldWrap(key, value, image) {
    var control = image
      ? '<div class="image-pick" data-image-field><img alt="" data-preview' + (value ? ' src="' + esc(publicSrc(value)) + '"' : " hidden") + '><input type="hidden" data-key="' + esc(key) + '" value="' + esc(value) + '">' + iconBtn("image", "Choose image", "data-pick-image") + "</div>"
      : '<input data-key="' + esc(key) + '" value="' + esc(value) + '">';
    return '<div class="field"><label>' + esc(key) + '</label>' + control + "</div>";
  }

  function renderRepeater(box, items) {
    var keys = (box.getAttribute("data-keys") || "title,text").split(",");
    var images = (box.getAttribute("data-images") || "").split(",").filter(Boolean);
    box.innerHTML = "";
    (items.length ? items : [{}]).forEach(function (item) {
      box.appendChild(repeaterRow(keys, images, item));
    });
  }

  function repeaterRow(keys, images, item) {
    var row = document.createElement("div");
    row.className = "repeater-row";
    row.setAttribute("data-row", "");
    row.innerHTML = keys.map(function (key) {
      return fieldWrap(key, item[key] || "", images.indexOf(key) !== -1);
    }).join("") +
      '<div class="repeater-actions">' +
      iconBtn("up", "Move up", "data-up") +
      iconBtn("down", "Move down", "data-down") +
      iconBtn("trash", "Remove", "data-remove") + "</div>";
    return row;
  }

  function moveRow(row, dir) {
    var box = row.parentNode;
    var sibling = dir < 0 ? row.previousElementSibling : row.nextElementSibling;
    if (!sibling) return;
    if (dir < 0) box.insertBefore(row, sibling);
    else box.insertBefore(sibling, row);
  }

  function validate(form) {
    var ok = true;
    form.querySelectorAll("[data-required]").forEach(function (field) {
      var empty = !String(field.value || "").trim();
      field.classList.toggle("field-error", empty);
      if (empty) ok = false;
    });
    return ok;
  }

  function moduleData(form) {
    var data = store.get(form.getAttribute("data-store"));
    var slice = form.getAttribute("data-slice");
    return slice ? (data[slice] || {}) : data;
  }

  function saveModule(form, next) {
    var name = form.getAttribute("data-store");
    var slice = form.getAttribute("data-slice");
    if (!slice) {
      store.set(name, next);
      return;
    }
    var data = store.get(name);
    data[slice] = next;
    store.set(name, data);
  }

  function bindForm(form) {
    var current = moduleData(form);
    writeBound(form, current);
    form.addEventListener("input", function () { markDirty(form); });
    form.addEventListener("change", function () { markDirty(form); });
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!validate(form)) {
        toast("Fill the required fields.");
        return;
      }
      var next = moduleData(form);
      var collected = readBound(form);
      Object.keys(collected).forEach(function (key) { next[key] = collected[key]; });
      saveModule(form, next);
      if (form.getAttribute("data-store") === "settings") {
        applyTheme(next.theme);
        try {
          localStorage.setItem("it-contact-pins", JSON.stringify({
            office: next.officePin || "",
            mill: next.millPin || ""
          }));
        } catch (err) {}
        renderSocialPreview();
      }
      clearDirty(form);
      toast("Saved in this browser. The public site is unchanged.");
    });
    form.querySelectorAll("[data-add]").forEach(function (button) {
      button.addEventListener("click", function () {
        var box = form.querySelector('[data-repeater="' + button.getAttribute("data-add") + '"]');
        if (!box) return;
        var keys = (box.getAttribute("data-keys") || "").split(",");
        box.appendChild(repeaterRow(keys, (box.getAttribute("data-images") || "").split(","), {}));
        markDirty(form);
      });
    });
    form.addEventListener("click", function (event) {
      var row = event.target.closest("[data-row]");
      if (!row) return;
      if (event.target.closest("[data-remove]")) {
        if (row.parentNode.querySelectorAll("[data-row]").length === 1) {
          row.querySelectorAll("[data-key]").forEach(function (field) { field.value = ""; });
        } else row.remove();
        markDirty(form);
      }
      if (event.target.closest("[data-up]")) moveRow(row, -1);
      if (event.target.closest("[data-down]")) moveRow(row, 1);
    });
  }

  function slugify(value) {
    return String(value || "").toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  }

  function bindSlug(form) {
    var title = form.querySelector("[data-slug-source]");
    var slug = form.querySelector("[data-slug]");
    if (!title || !slug) return;
    title.addEventListener("input", function () {
      if (slug.dataset.touched === "1") return;
      slug.value = slugify(title.value);
    });
    slug.addEventListener("input", function () { slug.dataset.touched = "1"; });
  }

  function recordList(form) {
    var module = form.getAttribute("data-record");
    var key = form.getAttribute("data-record-key");
    if (key) return ((store.get(module) || {})[key]) || [];
    var data = store.get(module);
    return Array.isArray(data) ? data : [];
  }

  function saveRecordList(form, list) {
    var module = form.getAttribute("data-record");
    var key = form.getAttribute("data-record-key");
    if (!key) {
      store.set(module, list);
      return;
    }
    var data = store.get(module) || {};
    data[key] = list;
    store.set(module, data);
  }

  function bindRecord(form) {
    var params = new URLSearchParams(location.search);
    var id = params.get("id");
    var list = recordList(form);
    var record = list.filter(function (row) { return row.id === id; })[0] || { id: id || ("id-" + Date.now()), status: "Draft" };
    writeBound(form, record);
    bindSlug(form);
    form.addEventListener("input", function () { markDirty(form); });
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!validate(form)) {
        toast("Fill the required fields.");
        return;
      }
      var next = readBound(form);
      next.id = record.id;
      if (!next.slug && next.title) next.slug = slugify(next.title);
      next.updated = new Date().toISOString().slice(0, 10);
      var fresh = recordList(form);
      var index = fresh.findIndex(function (row) { return row.id === next.id; });
      if (index >= 0) fresh[index] = Object.assign({}, fresh[index], next);
      else fresh.unshift(next);
      saveRecordList(form, fresh);
      clearDirty(form);
      toast("Saved in this browser. The public site is unchanged.");
      if (!params.get("id")) history.replaceState(null, "", location.pathname + "?id=" + encodeURIComponent(next.id));
    });
  }

  function statusPill(status) {
    var closed = status === "Closed" || status === "Draft";
    return '<span class="status-pill' + (closed ? " is-closed" : "") + '">' + esc(status || "") + "</span>";
  }

  var SITE_PAGES = [
    { title: "About", path: "about.html", edit: "page-about.html", image: "assets/images/products/product-0.jpg", summary: "Heritage, vision, timeline, and leadership initials", status: "Published" },
    { title: "Process", path: "process.html", edit: "page-process.html", image: "assets/images/process/step-05.jpg", summary: "Hero and six manufacturing stages", status: "Published" },
    { title: "Facilities", path: "facilities.html", edit: "page-facilities.html", image: "assets/images/products/product-0.jpg", summary: "Campus hero, capacity figures, and departments", status: "Published" },
    { title: "Quality", path: "quality.html", edit: "page-quality.html", image: "assets/images/gallery/gallery-4.jpg", summary: "Process points and buyer-request documents", status: "Published" },
    { title: "Sustainability", path: "sustainability.html", edit: "page-sustainability.html", image: "assets/images/gallery/gallery-1.jpg", summary: "Pillars and commitment", status: "Published" },
    { title: "Contact", path: "contact.html", edit: "page-contact.html", image: "assets/images/admin/contact-desk.jpg", summary: "Page title and intro. Addresses stay in Settings", status: "Published" },
    { title: "Privacy", path: "privacy.html", edit: "page-legal.html#privacy", image: "assets/images/admin/privacy-forms.jpg", summary: "How inquiry and career form data is used", status: "Published" },
    { title: "Terms", path: "terms.html", edit: "page-legal.html#terms", image: "assets/images/admin/privacy-forms.jpg", summary: "Website terms of use", status: "Published" },
    { title: "Quote success", path: "quote-success.html", edit: "page-legal.html#success", image: "assets/images/admin/quote-dispatch.jpg", summary: "Message after a quote form is sent", status: "Published" }
  ];

  function careerImage(row) {
    if (row.image) return row.image;
    var known = {
      qi: "assets/images/news/news-2.jpg",
      loom: "assets/images/products/product-0.jpg",
      merch: "assets/images/admin/career-samples.jpg",
      dye: "assets/images/gallery/gallery-5.jpg",
      spin: "assets/images/gallery/gallery-7.jpg"
    };
    if (known[row.id]) return known[row.id];
    var title = String(row.title || "").toLowerCase();
    if (title.indexOf("inspect") !== -1) return known.qi;
    if (title.indexOf("loom") !== -1) return known.loom;
    if (title.indexOf("merch") !== -1 || title.indexOf("sample") !== -1) return known.merch;
    if (title.indexOf("dye") !== -1) return known.dye;
    if (title.indexOf("spin") !== -1 || title.indexOf("yarn") !== -1) return known.spin;
    return "assets/images/products/product-0.jpg";
  }

  function inquiryImage(row) {
    if (row.image) return row.image;
    var known = {
      "inq-1": "assets/images/products/product-3.jpg",
      "inq-2": "assets/images/gallery/gallery-7.jpg",
      "inq-3": "assets/images/products/product-1.jpg"
    };
    if (known[row.id]) return known[row.id];
    var interest = String(row.interest || "").toLowerCase();
    if (interest.indexOf("yarn") !== -1) return known["inq-2"];
    if (interest.indexOf("finish") !== -1) return known["inq-1"];
    return known["inq-3"];
  }

  function categoryImage(row) {
    if (row.image) return row.image;
    var known = {
      yarn: "assets/images/gallery/gallery-7.jpg",
      woven: "assets/images/products/product-0.jpg",
      finished: "assets/images/products/product-3.jpg"
    };
    return known[row.id] || known.woven;
  }

  function pageRows() {
    return SITE_PAGES.map(function (page) {
      return {
        title: page.title,
        path: page.path,
        edit: page.edit,
        image: page.image,
        summary: page.summary,
        status: page.status
      };
    });
  }

  function renderList(node) {
    var kind = node.getAttribute("data-list");
    var query = ((document.querySelector('[data-list-search="' + kind + '"]') || {}).value || "").toLowerCase();
    var status = (document.querySelector('[data-list-status="' + kind + '"]') || {}).value || "";
    var rows = [];
    if (kind === "products") rows = store.get("products") || [];
    if (kind === "news") rows = store.get("news") || [];
    if (kind === "gallery") rows = (store.get("gallery") || []).slice().sort(function (a, b) { return (a.sort || 0) - (b.sort || 0); });
    if (kind === "careers") rows = (store.get("careers") || {}).jobs || [];
    if (kind === "inquiries") rows = store.get("inquiries") || [];
    if (kind === "categories") rows = store.get("categories") || [];
    if (kind === "applications") rows = (store.get("careers") || {}).applications || [];
    if (kind === "pages") rows = pageRows();
    rows = rows.filter(function (row) {
      var blob = Object.keys(row).map(function (key) { return row[key]; }).join(" ").toLowerCase();
      if (query && blob.indexOf(query) === -1) return false;
      if (status && row.status !== status) return false;
      return true;
    });
    var count = document.querySelector('[data-list-count="' + kind + '"]');
    if (count) count.textContent = rows.length + " shown";
    if (!rows.length) {
      node.innerHTML = '<tr><td colspan="8"><div class="empty-state">Nothing matches. Empty rows stay hidden on the site when this is wired.</div></td></tr>';
      return;
    }
    node.innerHTML = rows.map(function (row) { return listRow(kind, row); }).join("");
  }

  function listRow(kind, row) {
    if (kind === "pages") {
      return "<tr><td>" + thumb(row.image, row.title) + "</td><td>" + esc(row.title) + "</td><td>" + esc(row.path) + "</td><td>" + esc(row.summary) + "</td><td>" + statusPill(row.status) + "</td><td>" +
        actionsHtml([
          iconBtn("view", "View on site", 'href="../public-site/' + esc(row.path) + '" target="_blank" rel="noopener"'),
          iconBtn("edit", "Edit", 'href="' + esc(row.edit) + '"')
        ]) + "</td></tr>";
    }
    if (kind === "products") {
      var productView = row.slug === "combed-cotton-poplin"
        ? "../public-site/products/combed-cotton-poplin.html"
        : (row.category === "Yarn" ? "../public-site/products/index.html#yarn" : (row.category === "Finished Fabric" ? "../public-site/products/index.html#finishing" : "../public-site/products/woven-fabric.html"));
      return "<tr><td>" + thumb(row.image, row.title) + "</td><td>" + esc(row.title) + "</td><td>" + esc(row.category) + "</td><td>" + statusPill(row.status) + "</td><td>" + esc(row.updated || "") + "</td><td>" +
        actionsHtml([
          iconBtn("view", "View on site", 'href="' + productView + '" target="_blank" rel="noopener"'),
          iconBtn("edit", "Edit", 'href="product-form.html?id=' + esc(row.id) + '"'),
          iconBtn("trash", "Delete", 'data-delete="products" data-id="' + esc(row.id) + '"')
        ]) + "</td></tr>";
    }
    if (kind === "news") {
      var newsView = row.slug === "mill-efficiency-upgrade"
        ? "../public-site/news/mill-efficiency-upgrade.html"
        : "../public-site/news/index.html";
      return "<tr><td>" + thumb(row.cover, row.title) + "</td><td>" + esc(row.date) + "</td><td>" + esc(row.title) + "</td><td>" + statusPill(row.status) + "</td><td>" +
        actionsHtml([
          iconBtn("view", "View on site", 'href="' + newsView + '" target="_blank" rel="noopener"'),
          iconBtn("edit", "Edit", 'href="news-form.html?id=' + esc(row.id) + '"'),
          iconBtn("trash", "Delete", 'data-delete="news" data-id="' + esc(row.id) + '"')
        ]) + "</td></tr>";
    }
    if (kind === "gallery") {
      return "<tr><td>" + thumb(row.image, row.alt) + "</td><td>" + esc(row.album) + "</td><td>" + esc(row.caption) + "</td><td>" + esc(row.alt) + "</td><td>" + esc(row.sort) + "</td><td>" +
        actionsHtml([
          iconBtn("view", "View image", 'href="' + esc(publicSrc(row.image)) + '" target="_blank" rel="noopener"'),
          iconBtn("trash", "Delete", 'data-delete="gallery" data-id="' + esc(row.id) + '"')
        ]) + "</td></tr>";
    }
    if (kind === "careers") {
      var jobView = row.id === "qi" ? "../public-site/careers/quality-inspector.html" : "../public-site/careers/index.html";
      return "<tr><td>" + thumb(careerImage(row), row.title) + "</td><td>" + esc(row.title) + "</td><td>" + esc(row.location) + "</td><td>" + statusPill(row.status) + "</td><td>" +
        actionsHtml([
          iconBtn("view", "View on site", 'href="' + jobView + '" target="_blank" rel="noopener"'),
          iconBtn("edit", "Edit", 'href="career-form.html?id=' + esc(row.id) + '"'),
          iconBtn("trash", "Delete", 'data-delete="careers" data-id="' + esc(row.id) + '"')
        ]) + "</td></tr>";
    }
    if (kind === "inquiries") {
      return "<tr><td>" + thumb(inquiryImage(row), row.interest) + "</td><td>" + esc(row.date) + "</td><td>" + esc(row.company) + "</td><td>" + esc(row.interest) + "</td><td><select data-inquiry=\"" + esc(row.id) + "\"><option" + (row.status === "New" ? " selected" : "") + ">New</option><option" + (row.status === "In progress" ? " selected" : "") + ">In progress</option><option" + (row.status === "Closed" ? " selected" : "") + ">Closed</option></select></td></tr>";
    }
    if (kind === "categories") {
      return "<tr><td>" + thumb(categoryImage(row), row.name) + "</td><td>" + esc(row.name) + "</td><td>" + esc(row.text) + "</td><td>" + esc(row.href) + "</td></tr>";
    }
    if (kind === "applications") {
      return "<tr><td>" + esc(row.name) + "</td><td>" + esc(row.role) + "</td><td>" + esc(row.date) + "</td></tr>";
    }
    return "";
  }

  function deleteRow(kind, id) {
    if (!window.confirm("Delete this item from the admin list? The public page is not rewritten.")) return;
    if (kind === "careers") {
      var careers = store.get("careers");
      careers.jobs = (careers.jobs || []).filter(function (row) { return row.id !== id; });
      store.set("careers", careers);
    } else {
      store.set(kind, (store.get(kind) || []).filter(function (row) { return row.id !== id; }));
    }
    refreshLists();
    toast("Removed from this browser list.");
  }

  function refreshLists() {
    document.querySelectorAll("[data-list]").forEach(renderList);
    updateBadge();
    renderDashboard();
  }

  function renderDashboard() {
    var box = document.querySelector("[data-dashboard]");
    if (!box) return;
    var counts = {
      products: (store.get("products") || []).length,
      inquiries: newCount(),
      news: (store.get("news") || []).length,
      gallery: (store.get("gallery") || []).length
    };
    box.querySelectorAll("[data-count]").forEach(function (node) {
      node.textContent = String(counts[node.getAttribute("data-count")] || 0);
    });
    var body = box.querySelector("[data-list]");
    if (body) renderList(body);
  }

  function bindLists() {
    document.querySelectorAll("[data-list-search], [data-list-status]").forEach(function (field) {
      field.addEventListener("input", refreshLists);
      field.addEventListener("change", refreshLists);
    });
    document.addEventListener("click", function (event) {
      var button = event.target.closest("[data-delete]");
      if (!button) return;
      event.preventDefault();
      deleteRow(button.getAttribute("data-delete"), button.getAttribute("data-id"));
    });
    document.addEventListener("change", function (event) {
      var select = event.target.closest("[data-inquiry]");
      if (!select) return;
      var list = store.get("inquiries");
      list.forEach(function (row) {
        if (row.id === select.getAttribute("data-inquiry")) row.status = select.value;
      });
      store.set("inquiries", list);
      updateBadge();
      renderDashboard();
      toast("Inquiry status saved in this browser.");
    });
    refreshLists();
  }

  function bindTabs() {
    document.querySelectorAll(".tabs").forEach(function (tabs) {
      tabs.addEventListener("click", function (event) {
        var button = event.target.closest("[data-tab]");
        if (!button) return;
        var tab = button.getAttribute("data-tab");
        tabs.querySelectorAll("[data-tab]").forEach(function (item) {
          item.classList.toggle("is-active", item === button);
        });
        document.querySelectorAll("[data-panel]").forEach(function (panel) {
          panel.hidden = panel.getAttribute("data-panel") !== tab;
        });
      });
    });
  }

  function syncImagePreview(box) {
    var input = box.querySelector("[data-key], [name]");
    var img = box.querySelector("[data-preview]");
    if (!input || !img) return;
    img.src = publicSrc(input.value);
    img.hidden = !input.value;
  }

  function openPicker(target) {
    var media = store.get("media") || [];
    var back = document.createElement("div");
    back.className = "modal-back";
    back.innerHTML = '<div class="modal" role="dialog" aria-modal="true" aria-label="Choose image"><div class="panel-head"><h2>Choose an existing image</h2>' + iconBtn("close", "Close", "data-close") + '</div><div class="modal-grid"></div><p class="hint">These are the same mill photos used on the public site. No upload in this phase.</p></div>';
    var grid = back.querySelector(".modal-grid");
    grid.innerHTML = media.map(function (item) {
      return '<button type="button" data-file="' + esc(item.file) + '"><img src="' + esc(publicSrc(item.file)) + '" alt="' + esc(item.alt) + '"><span>' + esc(item.caption || item.alt) + "</span></button>";
    }).join("");
    document.body.appendChild(back);
    back.addEventListener("click", function (event) {
      if (event.target === back || event.target.closest("[data-close]")) back.remove();
      var pick = event.target.closest("[data-file]");
      if (!pick) return;
      target.value = pick.getAttribute("data-file");
      target.dispatchEvent(new Event("input", { bubbles: true }));
      var box = target.closest("[data-image-field]");
      if (box) syncImagePreview(box);
      back.remove();
    });
  }

  function bindPicker() {
    document.addEventListener("click", function (event) {
      var button = event.target.closest("[data-pick-image]");
      if (!button) return;
      var box = button.closest("[data-image-field]") || button.parentNode;
      var input = box.querySelector("input");
      if (input) openPicker(input);
    });
  }

  function syncColor(hex) {
    var color = hex.parentNode && hex.parentNode.querySelector("[data-color]");
    if (color && /^#[0-9a-fA-F]{6}$/.test(hex.value)) color.value = hex.value;
  }

  function bindTheme() {
    var settings = store.get("settings") || {};
    applyTheme(settings.theme);
    document.querySelectorAll("[data-color]").forEach(function (color) {
      var hex = color.parentNode.querySelector("[data-hex]");
      if (hex && hex.value) color.value = hex.value;
      color.addEventListener("input", function () {
        if (hex) hex.value = color.value;
        paintThemeFromForm();
      });
    });
    document.querySelectorAll("[data-hex]").forEach(function (hex) {
      hex.addEventListener("input", function () {
        syncColor(hex);
        paintThemeFromForm();
      });
    });
    var reset = document.querySelector("[data-reset-theme]");
    if (reset) {
      reset.addEventListener("click", function () {
        var theme = store.seed().settings.theme;
        Object.keys(theme).forEach(function (key) {
          var hex = document.querySelector('[name="theme.' + key + '"]');
          if (hex) hex.value = theme[key];
        });
        document.querySelectorAll("[data-hex]").forEach(function (hex) {
          syncColor(hex);
          hex.dispatchEvent(new Event("input", { bubbles: true }));
        });
        applyTheme(theme);
      });
    }
  }

  function paintThemeFromForm() {
    var theme = {};
    ["primary", "deep", "accent", "surface"].forEach(function (key) {
      var field = document.querySelector('[name="theme.' + key + '"]');
      if (field) theme[key] = field.value;
    });
    applyTheme(theme);
  }

  function renderSocialPreview() {
    var box = document.querySelector("[data-social-preview]");
    if (!box) return;
    var rows = [];
    document.querySelectorAll('[name^="social."]').forEach(function (field) {
      if (String(field.value || "").trim()) rows.push(field.name.split(".")[1] + ": " + field.value.trim());
    });
    box.innerHTML = rows.length
      ? rows.map(function (line) { return "<li>" + esc(line) + "</li>"; }).join("")
      : "<li>No social links yet — empty rows stay off the site.</li>";
  }

  var mediaState = { tab: "all", folder: "All", sort: "name", selected: {}, active: "" };
  var mediaBound = false;

  function mediaFolder(item) {
    if (item.folder) return item.folder;
    var file = item.file || "";
    if (file.indexOf("/products/") !== -1) return "Products";
    if (file.indexOf("/process/") !== -1) return "Production";
    if (file.indexOf("/facilities/") !== -1) return "Factory";
    if (file.indexOf("/news/") !== -1) return "News";
    if (file.indexOf("/admin/") !== -1) return "Office";
    if (file.indexOf("/gallery/") !== -1) return "Gallery";
    return "Library";
  }

  function extraFolders() {
    var list = store.get("mediaFolders");
    return Array.isArray(list) ? list.filter(Boolean) : [];
  }

  function mediaType(file) {
    var ext = String(file || "").split(".").pop().toLowerCase();
    if (ext === "png") return "Image (PNG)";
    if (ext === "webp") return "Image (WEBP)";
    if (ext === "jpeg" || ext === "jpg") return "Image (JPG)";
    return "Image";
  }

  function folderIcon(all) {
    if (all) {
      return '<svg class="media-folder__icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.2"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.2"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.2"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.2"/></svg>';
    }
    return '<svg class="media-folder__icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3.2 7.2h5.4l1.7 1.9h10.5v8.4a1.6 1.6 0 0 1-1.6 1.6H4.8a1.6 1.6 0 0 1-1.6-1.6V7.2z"/></svg>';
  }

  function mediaItems() {
    return (store.get("media") || []).map(function (item) {
      item.folder = mediaFolder(item);
      return item;
    });
  }

  function mediaVisible() {
    var query = ((document.querySelector("[data-media-search]") || {}).value || "").toLowerCase();
    return mediaItems().filter(function (item) {
      if (mediaState.tab === "videos" || mediaState.tab === "documents") return false;
      if (mediaState.folder !== "All" && item.folder !== mediaState.folder) return false;
      var blob = (item.file + " " + item.alt + " " + item.caption).toLowerCase();
      return !query || blob.indexOf(query) !== -1;
    }).sort(function (a, b) {
      var key = mediaState.sort === "caption" ? "caption" : "file";
      return String(a[key] || "").localeCompare(String(b[key] || ""));
    });
  }

  function paintMediaDetails(item) {
    var panel = document.querySelector("[data-media-details]");
    if (!panel) return;
    var preview = panel.querySelector("[data-media-preview]");
    if (!item) {
      if (window.matchMedia("(max-width: 1099px)").matches) panel.hidden = true;
      preview.removeAttribute("src");
      preview.hidden = true;
      panel.querySelector('[data-detail="name"]').textContent = "Select a file";
      panel.querySelector('[data-detail="type"]').textContent = "—";
      panel.querySelector('[data-detail="folder"]').textContent = "—";
      panel.querySelector('[data-detail="size"]').textContent = "—";
      panel.querySelector("[data-detail-alt]").value = "";
      panel.querySelector("[data-detail-caption]").value = "";
      return;
    }
    panel.hidden = false;
    preview.hidden = false;
    preview.alt = item.alt || "";
    panel.querySelector('[data-detail="name"]').textContent = item.file.split("/").pop();
    panel.querySelector('[data-detail="type"]').textContent = mediaType(item.file);
    panel.querySelector('[data-detail="folder"]').textContent = mediaFolder(item);
    panel.querySelector('[data-detail="size"]').textContent = "Reading…";
    panel.querySelector("[data-detail-alt]").value = item.alt || "";
    panel.querySelector("[data-detail-caption]").value = item.caption || "";
    function writeSize() {
      if (!preview.naturalWidth) return;
      panel.querySelector('[data-detail="size"]').textContent = preview.naturalWidth + " × " + preview.naturalHeight;
    }
    preview.onload = writeSize;
    preview.src = publicSrc(item.file);
    if (preview.complete) writeSize();
  }

  function renderMedia() {
    var grid = document.querySelector("[data-media]");
    var folders = document.querySelector("[data-media-folders]");
    if (!grid || !folders) return;
    var items = mediaItems();
    var groups = { All: items.length };
    items.forEach(function (item) {
      groups[item.folder] = (groups[item.folder] || 0) + 1;
    });
    extraFolders().forEach(function (name) {
      if (!groups[name]) groups[name] = 0;
    });
    var preferred = ["Factory", "Production", "Products", "Gallery", "News", "Office", "Library"];
    var names = ["All"].concat(
      preferred.filter(function (name) { return groups[name] != null; }),
      Object.keys(groups).filter(function (name) {
        return name !== "All" && preferred.indexOf(name) === -1;
      }).sort()
    );
    folders.innerHTML = names.map(function (name) {
      var all = name === "All";
      var chev = all ? "" : '<svg class="media-folder__chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>';
      return '<button type="button" class="media-folder' + (mediaState.folder === name ? " is-active" : "") + '" data-folder="' + esc(name) + '">' +
        folderIcon(all) +
        '<span class="media-folder__name">' + esc(all ? "All Media" : name) + "</span>" +
        '<span class="media-folder__count">' + groups[name] + "</span>" +
        chev + "</button>";
    }).join("");
    var visible = mediaVisible();
    if (!mediaState.active && visible[0]) mediaState.active = visible[0].id;
    var emptyNote = mediaState.tab === "videos"
      ? "No videos in the library."
      : (mediaState.tab === "documents" ? "No documents in the library." : "Nothing in this folder.");
    grid.innerHTML = visible.length ? visible.map(function (item) {
      var on = !!mediaState.selected[item.id];
      var active = mediaState.active === item.id ? " is-selected" : "";
      return '<article class="media-card' + (on ? " is-selected" : "") + active + '" data-media-id="' + esc(item.id) + '">' +
        '<input class="media-card__check" type="checkbox" data-media-check="' + esc(item.id) + '"' + (on ? " checked" : "") + ' aria-label="Select ' + esc(item.caption || item.file) + '">' +
        '<img src="' + esc(publicSrc(item.file)) + '" alt="' + esc(item.alt) + '">' +
        '<div class="media-card__meta"><strong>' + esc(item.file.split("/").pop()) + "</strong><small>" + esc(item.caption || item.alt) + "</small></div></article>";
    }).join("") : '<div class="empty-state">' + emptyNote + "</div>";
    var count = Object.keys(mediaState.selected).length;
    var countNode = document.querySelector("[data-media-count]");
    if (countNode) countNode.textContent = count + " selected";
    ["data-media-download", "data-media-move", "data-media-delete"].forEach(function (attr) {
      var button = document.querySelector("[" + attr + "]");
      if (button) button.disabled = count === 0;
    });
    var all = document.querySelector("[data-media-all]");
    if (all) all.checked = visible.length > 0 && visible.every(function (item) { return mediaState.selected[item.id]; });
    paintMediaDetails(items.filter(function (item) { return item.id === mediaState.active; })[0]);
  }

  function bindMediaSave() {
    var board = document.querySelector("[data-media-board]");
    if (!board || mediaBound) return;
    mediaBound = true;
    board.addEventListener("click", function (event) {
      var folder = event.target.closest("[data-folder]");
      if (folder) {
        mediaState.folder = folder.getAttribute("data-folder");
        renderMedia();
        return;
      }
      var check = event.target.closest("[data-media-check]");
      if (check) {
        var id = check.getAttribute("data-media-check");
        if (check.checked) mediaState.selected[id] = true;
        else delete mediaState.selected[id];
        mediaState.active = id;
        renderMedia();
        return;
      }
      var card = event.target.closest("[data-media-id]");
      if (card) {
        mediaState.active = card.getAttribute("data-media-id");
        renderMedia();
      }
    });
    document.querySelectorAll("[data-media-tab]").forEach(function (button) {
      button.addEventListener("click", function () {
        mediaState.tab = button.getAttribute("data-media-tab");
        document.querySelectorAll("[data-media-tab]").forEach(function (item) {
          item.classList.toggle("is-active", item === button);
        });
        renderMedia();
      });
    });
    var sort = document.querySelector("[data-media-sort]");
    if (sort) sort.addEventListener("change", function () { mediaState.sort = sort.value; renderMedia(); });
    var search = document.querySelector("[data-media-search]");
    if (search) search.addEventListener("input", renderMedia);
    var all = document.querySelector("[data-media-all]");
    if (all) {
      all.addEventListener("change", function () {
        mediaVisible().forEach(function (item) {
          if (all.checked) mediaState.selected[item.id] = true;
          else delete mediaState.selected[item.id];
        });
        renderMedia();
      });
    }
    function closeMoveMenu() {
      var menu = document.querySelector("[data-media-move-menu]");
      if (menu) menu.hidden = true;
    }
    function openFolderForm() {
      var form = document.querySelector("[data-folder-form]");
      if (!form) return;
      form.hidden = false;
      var input = form.querySelector("input");
      if (input) input.focus();
    }
    function moveTargets() {
      var names = {};
      mediaItems().forEach(function (item) { names[item.folder] = true; });
      extraFolders().forEach(function (name) { names[name] = true; });
      return Object.keys(names).sort();
    }
    document.querySelectorAll("[data-media-folder]").forEach(function (button) {
      button.addEventListener("click", function (event) {
        event.preventDefault();
        openFolderForm();
      });
    });
    var folderForm = document.querySelector("[data-folder-form]");
    if (folderForm) {
      folderForm.addEventListener("submit", function (event) {
        event.preventDefault();
        var input = folderForm.querySelector("input");
        var name = String(input.value || "").trim();
        if (!name || /^all( media)?$/i.test(name)) {
          toast("Choose a different folder name.");
          return;
        }
        var taken = extraFolders().some(function (item) { return item.toLowerCase() === name.toLowerCase(); })
          || mediaItems().some(function (item) { return item.folder.toLowerCase() === name.toLowerCase(); });
        if (taken) {
          toast("That folder already exists.");
          return;
        }
        var next = extraFolders().concat(name);
        store.set("mediaFolders", next);
        mediaState.folder = name;
        input.value = "";
        folderForm.hidden = true;
        renderMedia();
        toast("Folder added in this browser.");
      });
    }
    document.querySelectorAll("[data-media-upload]").forEach(function (button) {
      button.addEventListener("click", function () {
        toast("No upload in this phase. Choose an existing library image.");
      });
    });
    var drop = document.querySelector(".media-drop");
    if (drop) {
      drop.addEventListener("dragover", function (event) {
        event.preventDefault();
        drop.classList.add("is-drag");
      });
      drop.addEventListener("dragleave", function () { drop.classList.remove("is-drag"); });
      drop.addEventListener("drop", function (event) {
        event.preventDefault();
        drop.classList.remove("is-drag");
        toast("No upload in this phase. Choose an existing library image.");
      });
    }
    var remove = document.querySelector("[data-media-delete]");
    if (remove) {
      remove.addEventListener("click", function () {
        var ids = Object.keys(mediaState.selected);
        if (!ids.length || !window.confirm("Remove the selected files from this library? The public site is not rewritten.")) return;
        store.set("media", (store.get("media") || []).filter(function (item) { return !mediaState.selected[item.id]; }));
        mediaState.selected = {};
        mediaState.active = "";
        renderMedia();
        toast("Removed from this browser library.");
      });
    }
    var download = document.querySelector("[data-media-download]");
    if (download) {
      download.addEventListener("click", function () {
        mediaItems().filter(function (item) { return mediaState.selected[item.id]; }).forEach(function (item) {
          var link = document.createElement("a");
          link.href = publicSrc(item.file);
          link.download = item.file.split("/").pop();
          link.rel = "noopener";
          document.body.appendChild(link);
          link.click();
          link.remove();
        });
      });
    }
    var move = document.querySelector("[data-media-move]");
    var moveMenu = document.querySelector("[data-media-move-menu]");
    if (move && moveMenu) {
      move.addEventListener("click", function (event) {
        event.stopPropagation();
        if (!Object.keys(mediaState.selected).length) return;
        var open = moveMenu.hidden;
        closeMoveMenu();
        if (!open) return;
        var targets = moveTargets();
        moveMenu.innerHTML = targets.length
          ? targets.map(function (name) {
            return '<button type="button" data-move-to="' + esc(name) + '">' + esc(name) + "</button>";
          }).join("")
          : "<p>No folders yet.</p>";
        moveMenu.hidden = false;
      });
      moveMenu.addEventListener("click", function (event) {
        var choice = event.target.closest("[data-move-to]");
        if (!choice) return;
        var folder = choice.getAttribute("data-move-to");
        var items = store.get("media") || [];
        items.forEach(function (item) {
          if (mediaState.selected[item.id]) item.folder = folder;
        });
        store.set("media", items);
        mediaState.folder = folder;
        closeMoveMenu();
        renderMedia();
        toast("Moved in this browser library.");
      });
      document.addEventListener("click", function (event) {
        if (!event.target.closest(".media-move")) closeMoveMenu();
      });
    }
    var save = document.querySelector("[data-save-media]");
    if (save) {
      save.addEventListener("click", function () {
        if (!mediaState.active) {
          toast("Select a file first.");
          return;
        }
        var items = store.get("media") || [];
        items.forEach(function (item) {
          if (item.id !== mediaState.active) return;
          item.alt = document.querySelector("[data-detail-alt]").value;
          item.caption = document.querySelector("[data-detail-caption]").value;
        });
        store.set("media", items);
        renderMedia();
        toast("Alt and caption saved in this browser.");
      });
    }
    var cancel = document.querySelector("[data-detail-cancel]");
    if (cancel) cancel.addEventListener("click", function () { renderMedia(); });
  }

  function bindPins() {
    var office = document.getElementById("office_pin");
    var mill = document.getElementById("mill_pin");
    var preview = document.getElementById("map-preview");
    if (!preview) return;
    function writePreview() {
      var params = new URLSearchParams();
      if (office && office.value.trim()) params.set("officePin", office.value.trim());
      if (mill && mill.value.trim()) params.set("millPin", mill.value.trim());
      var query = params.toString();
      preview.href = "../public-site/contact.html" + (query ? "?" + query : "");
    }
    writePreview();
    if (office) office.addEventListener("input", writePreview);
    if (mill) mill.addEventListener("input", writePreview);
  }

  function bindCategories() {
    var form = document.querySelector("[data-category-editor]");
    if (!form) return;
    var box = form.querySelector("[data-category-rows]");
    function paint() {
      var rows = store.get("categories") || [];
      box.innerHTML = rows.map(function (row, index) {
        return '<div class="repeater-row" data-cat="' + index + '"><div class="field"><label>Image</label><img class="list-thumb" src="' + esc(publicSrc(categoryImage(row))) + '" alt="' + esc(row.name) + '"></div><div class="field"><label>Name</label><input data-key="name" value="' + esc(row.name) + '"></div><div class="field"><label>Short text</label><input data-key="text" value="' + esc(row.text) + '"><span class="hide-note">Hidden on the site when empty</span></div><div class="field"><label>Link</label><input data-key="href" value="' + esc(row.href) + '"></div></div>';
      }).join("");
    }
    paint();
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      var next = [];
      box.querySelectorAll("[data-cat]").forEach(function (row, index) {
        var current = (store.get("categories") || [])[index] || { id: "cat-" + index };
        current.name = row.querySelector('[data-key="name"]').value;
        current.text = row.querySelector('[data-key="text"]').value;
        current.href = row.querySelector('[data-key="href"]').value;
        current.image = categoryImage(current);
        if (current.name.trim()) next.push(current);
      });
      store.set("categories", next);
      toast("Categories saved in this browser. The public site is unchanged.");
    });
  }

  function bindGalleryForm() {
    var form = document.querySelector("[data-gallery-form]");
    if (!form) return;
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      var item = readBound(form);
      if (!item.image && !item.caption && !item.alt) {
        toast("Add an image or caption first.");
        return;
      }
      item.id = "g-" + Date.now();
      item.sort = Number(item.sort || 0);
      var list = store.get("gallery") || [];
      list.push(item);
      store.set("gallery", list);
      form.reset();
      refreshLists();
      toast("Gallery item saved in this browser.");
    });
  }

  window.addEventListener("beforeunload", function (event) {
    if (!dirty) return;
    event.preventDefault();
    event.returnValue = "";
  });

  function decorateActions() {
    document.querySelectorAll("a.btn, button.btn").forEach(function (el) {
      if (el.closest(".login-card") || el.classList.contains("icon-btn") || el.classList.contains("is-iconized")) return;
      var label = (el.textContent || "").replace(/\s+/g, " ").trim();
      var icon = "";
      var keepText = false;
      if (/^(add|create)\b/i.test(label)) icon = "plus";
      else if (/^back\b/i.test(label)) icon = "back";
      else if (/^choose\b/i.test(label)) icon = "image";
      else if (/^save\b/i.test(label)) { icon = "check"; keepText = true; }
      if (!icon || !ICONS[icon]) return;
      el.setAttribute("aria-label", label);
      el.title = label;
      if (keepText) {
        el.classList.add("is-iconized", "btn--with-icon");
        el.insertAdjacentHTML("afterbegin", ICONS[icon]);
        return;
      }
      var primary = el.classList.contains("btn-primary");
      el.className = "icon-btn" + (primary ? " icon-btn--primary" : "");
      el.innerHTML = ICONS[icon];
      el.setAttribute("aria-label", label);
      el.title = label;
    });
  }

  document.querySelectorAll("form[data-store]").forEach(function (form) {
    if (form.hasAttribute("data-skip-bind")) return;
    bindForm(form);
  });
  document.querySelectorAll("form[data-record]").forEach(bindRecord);
  bindTabs();
  bindLists();
  bindPicker();
  bindTheme();
  bindPins();
  bindMediaSave();
  bindCategories();
  bindGalleryForm();
  document.querySelectorAll('[name^="social."]').forEach(function (field) {
    field.addEventListener("input", renderSocialPreview);
  });
  renderMedia();
  renderSocialPreview();
  decorateActions();
  updateBadge();
  window.ITAdminUI = { toast: toast, refresh: refreshLists };
})();
