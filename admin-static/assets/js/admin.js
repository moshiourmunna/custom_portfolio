(function () {
  var ICONS = {
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
    logout: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 7V5.5A1.5 1.5 0 0 1 11.5 4h7A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 10 18.5V17"/><path d="M4 12h10M11 8.5 14.5 12 11 15.5"/></svg>'
  };

  var GROUPS = [
    {
      label: "Overview",
      items: [
        { id: "dashboard", href: "dashboard.html", label: "Dashboard" },
        { id: "home", href: "home.html", label: "Home" }
      ]
    },
    {
      label: "Content",
      items: [
        { id: "pages", href: "pages.html", label: "Pages" },
        { id: "products", href: "products.html", label: "Products" },
        { id: "categories", href: "categories.html", label: "Categories" },
        { id: "gallery", href: "gallery.html", label: "Gallery" },
        { id: "news", href: "news.html", label: "News" },
        { id: "careers", href: "careers.html", label: "Careers" }
      ]
    },
    {
      label: "Library",
      items: [
        { id: "inquiries", href: "inquiries.html", label: "Inquiries" },
        { id: "media", href: "media.html", label: "Media" }
      ]
    },
    {
      label: "System",
      items: [
        { id: "settings", href: "settings.html", label: "Settings" }
      ]
    }
  ];

  var ITEMS = GROUPS.reduce(function (all, group) { return all.concat(group.items); }, []);

  function activeId() {
    var file = (location.pathname.split("/").pop() || "dashboard.html").toLowerCase();
    if (file.indexOf("page-") === 0 || file === "pages.html") return "pages";
    if (file === "product-form.html") return "products";
    if (file === "news-form.html") return "news";
    if (file === "career-form.html") return "careers";
    var hit = ITEMS.filter(function (item) { return item.href === file; })[0];
    return hit ? hit.id : "";
  }

  function linkHtml(item, active) {
    var on = item.id === active;
    return '<a class="sidebar__link' + (on ? " is-active" : "") + '"' +
      (on ? ' aria-current="page"' : "") +
      ' href="' + item.href + '"><span class="sidebar__icon">' + ICONS[item.id] + "</span><span>" + item.label + "</span></a>";
  }

  function renderSidebar(sidebar) {
    var active = activeId();
    var groups = GROUPS.map(function (group) {
      return '<p class="sidebar__label">' + group.label + "</p>" +
        group.items.map(function (item) { return linkHtml(item, active); }).join("");
    }).join("");
    sidebar.innerHTML =
      '<nav class="sidebar__nav" aria-label="Admin">' + groups + "</nav>" +
      '<div class="sidebar__foot">' +
        '<p class="sidebar__mill"><strong>Islam Textile</strong><span>Dhaka · Narayanganj</span></p>' +
        '<a class="sidebar__link sidebar__link--logout" href="login.html"><span class="sidebar__icon">' + ICONS.logout + "</span><span>Logout</span></a>" +
      "</div>";
  }

  var sidebar = document.querySelector(".sidebar");
  if (sidebar && !document.body.classList.contains("login-page")) renderSidebar(sidebar);

  var toggle = document.querySelector("[data-nav-toggle]");
  var backdrop = document.querySelector("[data-nav-backdrop]");
  if (!toggle || !sidebar) return;

  function setOpen(open) {
    sidebar.classList.toggle("is-open", open);
    document.body.classList.toggle("nav-open", open);
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    if (backdrop) backdrop.hidden = !open;
  }

  toggle.addEventListener("click", function () {
    setOpen(!sidebar.classList.contains("is-open"));
  });

  if (backdrop) {
    backdrop.addEventListener("click", function () {
      setOpen(false);
    });
  }

  sidebar.addEventListener("click", function (event) {
    if (event.target.closest("a")) setOpen(false);
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") setOpen(false);
  });
})();
