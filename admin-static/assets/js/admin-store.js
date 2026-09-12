/* Islam Textile admin store. Public pages do not read this. */
(function () {
  var KEY = "it-admin-v2";

  function clone(value) {
    return JSON.parse(JSON.stringify(value));
  }

  function seed() {
    return {
      settings: {
        siteName: "Islam Textile",
        tagline: "Weaving Tradition, Ensuring Quality",
        logo: "01",
        favicon: "favicon.png",
        website: "www.islamtextile.com",
        canonicalBase: "https://www.islamtextile.com",
        footerBlurb: "Premium cotton yarn, woven fabric, and finishing from Dhaka and Narayanganj, Bangladesh.",
        hours: "Sunday–Thursday, 9:00–18:00 (BST)",
        officeAddress: "[Street / Area], Dhaka, Bangladesh",
        millAddress: "[Factory Road], Narayanganj, Bangladesh",
        phone: "+880 1XXX-XXXXXX",
        email: "info@islamtextile.com",
        officePin: "",
        millPin: "",
        metaTitle: "Islam Textile | Premium Cotton Woven Textiles",
        metaDescription: "Cotton yarn, woven fabric, and dyeing & finishing from Bangladesh.",
        ogImage: "assets/images/products/product-0.jpg",
        theme: {
          primary: "#004d40",
          deep: "#003d33",
          accent: "#548c84",
          surface: "#f4f7f6"
        },
        social: {
          facebook: "",
          instagram: "",
          linkedin: "",
          youtube: "",
          whatsapp: "",
          x: ""
        },
        tracking: {
          ga: "",
          gtm: "",
          metaPixel: "",
          metaDomain: "",
          gsc: "",
          bing: ""
        },
        maintenance: false
      },
      home: {
        hero: {
          image: "assets/images/products/product-0.jpg",
          headline: "Premium Cotton. Woven to Perfection.",
          lead: "Reliable quality, sustainable practices, and on-time delivery you can count on.",
          primaryLabel: "Request Quote",
          primaryHref: "contact.html",
          secondaryLabel: "Explore Products",
          secondaryHref: "products/index.html"
        },
        stats: [
          { value: "500", suffix: "+", label: "Looms Installed" },
          { value: "18000", suffix: "+", label: "MT Annual Capacity" },
          { value: "25", suffix: "+", label: "Countries Served" },
          { value: "800", suffix: "+", label: "Skilled Employees" }
        ],
        products: [
          { title: "Yarn", text: "High-quality cotton yarns for superior strength and consistent performance.", href: "products/index.html#yarn" },
          { title: "Woven Fabric", text: "Durable, versatile fabrics crafted with precision and care.", href: "products/woven-fabric.html" },
          { title: "Finished Fabric", text: "Ready-to-use fabrics with quality finishing for your exact needs.", href: "products/index.html#finishing" }
        ],
        insights: [
          { title: "From Fiber to Finish", text: "An integrated cotton pathway — spinning, weaving, and finishing — under one quality system in Bangladesh.", href: "process.html", linkLabel: "Discover Our Process", image: "assets/images/process/step-06.jpg" },
          { title: "Sustainability", text: "Responsible water, energy, and cotton practices across our Narayanganj mill operations.", href: "sustainability.html", linkLabel: "Our Commitment", image: "assets/images/gallery/gallery-1.jpg" },
          { title: "Latest News", text: "", href: "news/index.html", linkLabel: "View All News", image: "assets/images/gallery/gallery-8.jpg" }
        ],
        why: {
          title: "Trusted by buyers who need consistency",
          lead: "Focused on cotton woven textiles — not apparel retail — so quality, capacity, and delivery stay aligned for export programs.",
          items: [
            { title: "Quality First", text: "In-process checks and in-house lab testing for construction, shade, and handfeel." },
            { title: "Scalable Capacity", text: "Spinning, weaving, and finishing sized for repeat bulk orders and seasonal peaks." },
            { title: "On-Time Delivery", text: "Program planning from Dhaka with mill execution in Narayanganj and Chattogram logistics." },
            { title: "Export Ready", text: "Buyer-facing specs, packing standards, and documentation for global apparel and home textile markets." }
          ]
        },
        integration: {
          title: "From yarn to finished fabric",
          lead: "One coordinated mill pathway — fewer handoffs, clearer accountability, and batch identity from the first bale to the packed roll.",
          steps: [
            { title: "Fiber", text: "Cotton opened and carded for length and cleanliness.", meta: "Opening · Carding" },
            { title: "Spinning", text: "Ring-spun yarn with controlled twist, evenness, and strength.", meta: "Ne 20–40" },
            { title: "Weaving", text: "Greige woven to spec on air-jet and rapier looms, then inspected.", meta: "Air-jet · Rapier" },
            { title: "Dyeing", text: "Reactive and pigment shades held to approved lab dips.", meta: "Shade control" },
            { title: "Finishing", text: "Mercerize, sanforize, and soften for hand and shrinkage.", meta: "Mercer · Sanfor" },
            { title: "Dispatch", text: "Lab-checked rolls packed with batch documents for export.", meta: "Export packing" }
          ]
        },
        facilities: {
          title: "Built for reliable bulk production",
          lead: "One Narayanganj campus for spinning, weaving, and wet processing — with Dhaka program coordination and Chattogram export logistics.",
          image: "assets/images/products/product-0.jpg",
          bullets: [
            { title: "Continuous utilities", text: "Boiler, compressed air, and power backup sized for 24/7 running." },
            { title: "Export packing", text: "Inspected rolls and buyer packing specs before dispatch." },
            { title: "Dhaka buyer desk", text: "Commercial follow-up while the mill holds the lot." }
          ]
        },
        quality: {
          title: "Standards woven into every stage",
          lead: "From yarn evenness to packed-roll inspection — quality is a mill culture, not a last-minute gate.",
          points: [
            { title: "In-house lab", text: "Tensile, GSM, colorfastness, yarn U%, and shade ΔE before shipment." },
            { title: "Process control", text: "Yarn check, loom-side inspection, and 4-point fabric mapping on the lot." },
            { title: "Buyer specs", text: "Construction, hand, and shade panels held to apparel and home textile programs." },
            { title: "Audit ready", text: "Test reports and packing documents available for buyer and audit review." }
          ]
        },
        markets: {
          title: "Bangladesh-made cotton for global programs",
          lead: "Greige and finished woven cotton for buyers who need the same construction, hand, and shade on the next order.",
          items: [
            { title: "Apparel", text: "Poplin and oxford for shirting, uniforms, and linings." },
            { title: "Home textile", text: "Sheeting and soft furnishings in greige or finished cotton." },
            { title: "Workwear", text: "Durable plain and twill built for repeat bulk programs." },
            { title: "Industrial", text: "Spec-driven width, GSM, and construction to the buyer brief." }
          ]
        },
        gallery: {
          title: "Inside the mill",
          lead: "Documented floors at Narayanganj — weaving, dyeing, finishing, and utilities — without portraits."
        },
        careers: {
          title: "Build your career in textiles",
          lead: "Join a Bangladesh cotton mill team focused on quality manufacturing, continuous improvement, and safe operations in Narayanganj.",
          button: "View Careers"
        },
        cta: {
          title: "Let's Build Something Great Together",
          lead: "Share constructions, finishes, and volumes — our Dhaka commercial team will respond with next steps."
        }
      },
      pages: {
        about: {
          heritageTitle: "Three decades of cotton expertise",
          heritageText: "Founded in Narayanganj, Islam Textile grew from a weaving shed into an integrated mill — spinning, weaving, and dyeing & finishing under one quality system.",
          heritageMore: "The work stays on cotton woven textiles. Buyers get one accountable partner, lot identity from fiber to packed roll, and lead times they can plan around.",
          image: "assets/images/gallery/gallery-2.jpg",
          vision: "Bangladesh’s trusted partner for cotton yarn and woven fabric — known for consistency and responsible manufacturing.",
          mission: "Export-ready cotton through integrated spinning, weaving, and finishing — with clear communication on every program.",
          values: "Quality at every gate\nLong-term buyer partnerships\nContinuous mill improvement\nRespect for people and the mill",
          timeline: [
            { year: "1990", title: "Founded", text: "Weaving shed established in Narayanganj’s textile belt." },
            { year: "2002", title: "Spinning added", text: "In-house ring spinning for weaving-grade cotton yarn." },
            { year: "2010", title: "Air-jet expansion", text: "Loom hall commissioned for export greige capacity." },
            { year: "2016", title: "Dyehouse online", text: "Reactive dyeing and finishing for export-ready fabric." },
            { year: "2024", title: "Global reach", text: "Programs for buyers across 25+ countries." }
          ],
          leaders: [
            { initials: "RI", name: "Md. Rafiqul Islam", role: "Chairman", text: "Steward of the growth from weaving shed to integrated mill." },
            { initials: "NJ", name: "Nusrat Jahan", role: "Managing Director", text: "Leads buyer partnerships, commercial strategy, and export programs." },
            { initials: "TA", name: "Tanvir Ahmed", role: "Director, Operations", text: "Oversees spinning, weaving, and finishing capacity at Narayanganj." },
            { initials: "FR", name: "Farhana Rahman", role: "Director, Quality", text: "Owns the laboratory, lot records, and pre-dispatch assurance." }
          ]
        },
        process: {
          eyebrow: "How we make",
          title: "Our manufacturing process",
          lead: "Six integrated stages — from yarn on the rack to inspected, export-ready woven fabric at our Narayanganj mill. Every lot keeps its batch identity through to the packed roll.",
          image: "assets/images/process/step-05.jpg",
          stages: [
            { title: "Yarn preparation", text: "Ring-spun yarn is racked by lot and checked for count and cleanliness before it is beamed.", image: "assets/images/process/step-06.jpg" },
            { title: "Warping", text: "Yarn is wound onto warp beams and sized before it goes on the loom.", image: "assets/images/products/product-1.jpg" },
            { title: "Weaving", text: "Warping, sizing, and weaving on air-jet and rapier looms — poplin, twill, oxford, and plain.", image: "assets/images/products/product-0.jpg" },
            { title: "Inspection", text: "Greige is mapped on a lighted table before it moves to dyeing or packing.", image: "assets/images/news/news-2.jpg" },
            { title: "Dyeing & finishing", text: "Shade, hand, and shrinkage are held to the approved panel.", image: "assets/images/gallery/gallery-5.jpg" },
            { title: "Final QA & dispatch", text: "Lab-checked rolls leave with batch documents.", image: "assets/images/gallery/gallery-8.jpg" }
          ]
        },
        facilities: {
          title: "Built on one campus. Sized for export programs.",
          lead: "Spinning, weaving, and wet processing in Narayanganj — with a Dhaka desk for buyer programs and Chattogram for export logistics.",
          image: "assets/images/products/product-0.jpg",
          figures: [
            { value: "120000", suffix: "+", label: "Spindles" },
            { value: "480", suffix: "+", label: "Looms" },
            { value: "1200", suffix: "+", label: "Workforce" },
            { value: "25", suffix: "M+", label: "Yards / Year" }
          ],
          departments: [
            { title: "Spinning", text: "Ring spinning for weaving-grade cotton yarn." },
            { title: "Weaving", text: "Air-jet and rapier looms for greige woven cotton." },
            { title: "Wet processing", text: "Dyeing and finishing on the same campus." },
            { title: "Laboratory", text: "In-house checks before a lot is packed." },
            { title: "Warehouse", text: "Inspected rolls held for dispatch." },
            { title: "ETP", text: "Effluent treated before discharge." }
          ]
        },
        quality: {
          title: "Quality and certifications",
          line: "Quality is woven into the process — not added at the dock.",
          lead: "From yarn evenness to packed-roll inspection. Shade, strength, and documents stay with the shipment out of Narayanganj.",
          points: [
            { title: "Yarn check", text: "Evenness and strength before the beam" },
            { title: "Loom-side", text: "Greige defects caught on the weaving floor" },
            { title: "4-point", text: "Fabric mapped before dyeing or packing" },
            { title: "Laboratory", text: "GSM, strength, colorfastness, and shade" },
            { title: "Shade", text: "Held to the approved lab dip" },
            { title: "Dispatch", text: "Rolls and documents leave together" }
          ],
          documents: [
            { title: "Test report", note: "Buyer-request document — not an earned certificate" },
            { title: "Shade panel", note: "Buyer-request document — not an earned certificate" },
            { title: "Inspection map", note: "Buyer-request document — not an earned certificate" },
            { title: "Packing list", note: "Buyer-request document — not an earned certificate" },
            { title: "Quality notes", note: "Buyer-request document — not an earned certificate" },
            { title: "Audit pack", note: "Buyer-request document — not an earned certificate" }
          ]
        },
        sustainability: {
          title: "Sustainability",
          lead: "Responsible water, energy, and cotton practices across our Narayanganj mill operations.",
          pillars: [
            { title: "Water care", text: "Effluent is treated before discharge. Rinsing and liquor ratio are managed to cut freshwater use per meter." },
            { title: "Energy", text: "Heat is recovered on the finishing line, and loom and utility loads are tracked by shift." },
            { title: "Chemistry", text: "Dyestuffs and auxiliaries are held to an approved list, with MRSL screening and safe-handling practice." },
            { title: "Waste reduction", text: "Yarn and fabric scrap are separated, and export packing is tightened so less material leaves as waste." }
          ],
          commitmentTitle: "Measurable progress. Continuous improvement.",
          commitment: "Targets are set each year for water, energy, and chemical compliance. The figures that apply to a program are shared when a buyer requests the profile."
        },
        contact: {
          title: "Contact & Request Quote",
          lead: "Tell us about your yarn or woven fabric program — constructions, finishes, volumes, and target markets.",
          cardTitle: "Reach the mill",
          cardLead: "The Dhaka desk handles buyer programs. Mill visits in Narayanganj are by appointment."
        },
        privacy: {
          title: "Privacy Policy",
          updated: "12 September 2026",
          body: "Islam Textile respects your privacy. This page explains how information submitted through inquiry or career forms on this website is used to respond to your request.\n\n1. Information we collect\nWe collect contact details and project information you voluntarily provide, plus limited technical logs used for security.\n\n2. How we use data\nInformation is used to handle the request you sent: quotes, applications, support, site security, and legal obligations under Bangladesh law where applicable.\n\n3. Sharing\nWe do not sell form data. It is shared only as needed to respond to the request or where the law requires it.\n\n4. Retention\nInquiry records are kept only as long as needed to handle the request and any follow-up.\n\n5. Contact\nQuestions about this page: info@islamtextile.com"
        },
        terms: {
          title: "Terms of Use",
          lead: "These terms govern use of the Islam Textile corporate website. By using this website you agree to these terms.",
          body: "1. Acceptance of Terms\nBy using this website you agree to these terms. Content is provided for general business information about Islam Textile’s cotton yarn, woven fabric, and finishing capabilities in Bangladesh.\n\n2. Information Accuracy\nSpecifications, capacities, and imagery may be illustrative placeholders until confirmed in a formal quotation from our Dhaka commercial team.\n\n3. Intellectual Property\nSite design, text, photography, and brand marks belong to Islam Textile unless otherwise noted. Do not reproduce without written permission.\n\n4. Inquiries\nSubmitting a form does not create a contract. A quotation is issued separately by the commercial team.\n\n5. Limitation of Liability\nThe website is provided as general information. Confirm specifications in a formal quotation.\n\n6. Contact\ninfo@islamtextile.com"
        },
        success: {
          title: "Thank You",
          message: "Your inquiry has been recorded (front-end demo). When the admin backend is live, our Dhaka team will receive this automatically and respond with next steps."
        }
      },
      categories: [
        { id: "yarn", name: "Yarn", text: "High-quality cotton yarns for superior strength and consistent performance.", href: "products/index.html#yarn", image: "assets/images/gallery/gallery-7.jpg" },
        { id: "woven", name: "Woven Fabric", text: "Durable, versatile fabrics crafted with precision and care.", href: "products/woven-fabric.html", image: "assets/images/products/product-0.jpg" },
        { id: "finished", name: "Finished Fabric", text: "Ready-to-use fabrics with quality finishing for your exact needs.", href: "products/index.html#finishing", image: "assets/images/products/product-3.jpg" }
      ],
      products: [
        { id: "poplin", title: "Combed Cotton Poplin", slug: "combed-cotton-poplin", category: "Woven Fabric", status: "Published", summary: "Soft combed cotton poplin for shirts and light apparel programs.", description: "Greige poplin woven on the Narayanganj looms. Construction and shade are confirmed in a formal quotation.", count: "40s × 40s", weave: "Poplin", finish: "Greige", construction: "40×40 / 133×72", gsm: "130–145", image: "assets/images/products/product-0.jpg", metaTitle: "Combed Cotton Poplin | Islam Textile", metaDesc: "Combed cotton poplin woven fabric from Islam Textile.", updated: "2026-08-01" },
        { id: "fine-poplin", title: "Fine Count Poplin", slug: "fine-count-poplin", category: "Woven Fabric", status: "Published", summary: "Fine-count poplin for lighter shirting programs.", description: "Woven cotton poplin in a finer count. Confirm the construction against the buyer brief.", count: "60s × 60s", weave: "Poplin", finish: "RFD", construction: "60×60 / 173×124", gsm: "110–125", image: "assets/images/products/product-1.jpg", metaTitle: "Fine Count Poplin | Islam Textile", metaDesc: "Fine count cotton poplin from Islam Textile.", updated: "2026-07-15" },
        { id: "twill", title: "Cotton Twill 3/1", slug: "cotton-twill-3-1", category: "Woven Fabric", status: "Published", summary: "Coarser cotton twill for workwear and heavier programs.", description: "3/1 twill woven for durability. Finish and shade are confirmed on the order.", count: "20s × 16s", weave: "Twill", finish: "Dyed", construction: "20×16 / 120×60", gsm: "220–280", image: "assets/images/products/product-0.jpg", metaTitle: "Cotton Twill 3/1 | Islam Textile", metaDesc: "Cotton twill woven fabric from Islam Textile.", updated: "2026-06-20" },
        { id: "drill", title: "Cotton Drill", slug: "cotton-drill", category: "Finished Fabric", status: "Published", summary: "Finished cotton drill for repeat bulk programs.", description: "Finished drill held for hand and shade on standing programs.", count: "16s × 12s", weave: "Twill", finish: "Finished", construction: "16×12 / 108×56", gsm: "250–300", image: "assets/images/gallery/gallery-8.jpg", metaTitle: "Cotton Drill | Islam Textile", metaDesc: "Finished cotton drill from Islam Textile.", updated: "2026-05-10" },
        { id: "oxford", title: "Cotton Oxford", slug: "cotton-oxford", category: "Woven Fabric", status: "Published", summary: "Oxford weave for shirting and uniform programs.", description: "Cotton oxford woven to a buyer construction.", count: "30s × 30s", weave: "Oxford", finish: "Dyed", construction: "30×30 / 84×72", gsm: "140–160", image: "assets/images/products/product-1.jpg", metaTitle: "Cotton Oxford | Islam Textile", metaDesc: "Cotton oxford woven fabric from Islam Textile.", updated: "2026-04-02" },
        { id: "sheeting", title: "Plain Weave Sheeting", slug: "plain-weave-sheeting", category: "Woven Fabric", status: "Published", summary: "Plain-weave sheeting in greige cotton.", description: "Sheeting construction for home textile programs. Finish is confirmed on the order.", count: "30s × 30s", weave: "Plain", finish: "Greige", construction: "30×30 / 68×68", gsm: "120–140", image: "assets/images/gallery/gallery-2.jpg", metaTitle: "Plain Weave Sheeting | Islam Textile", metaDesc: "Plain weave cotton sheeting from Islam Textile.", updated: "2026-03-12" },
        { id: "yarn-20", title: "Carded OE Yarn 20s", slug: "carded-oe-yarn-20s", category: "Yarn", status: "Published", summary: "Carded open-end cotton yarn for weaving programs.", description: "Weaving-grade cotton yarn. Count and package are confirmed in the quotation.", count: "20s", weave: "", finish: "", construction: "", gsm: "", image: "assets/images/process/step-06.jpg", metaTitle: "Carded OE Yarn 20s | Islam Textile", metaDesc: "Carded cotton yarn from Islam Textile.", updated: "2026-09-05" }
      ],
      gallery: [
        { id: "g1", album: "Factory", image: "assets/images/gallery/gallery-1.jpg", alt: "Effluent treatment plant at the Narayanganj campus", caption: "Effluent treatment plant", sort: 1 },
        { id: "g7", album: "Yarn", image: "assets/images/gallery/gallery-7.jpg", alt: "Yarn rolls stored on racking", caption: "Yarn rolls in store", sort: 2 },
        { id: "g2", album: "Yarn", image: "assets/images/gallery/gallery-2.jpg", alt: "Fabric on the finishing line", caption: "Fabric on the finishing line", sort: 3 },
        { id: "g4", album: "Production", image: "assets/images/gallery/gallery-4.jpg", alt: "Color dispensing laboratory", caption: "Color dispensing laboratory", sort: 4 },
        { id: "g0", album: "Production", image: "assets/images/gallery/gallery-0.jpg", alt: "Dyeing machines on the wet-processing floor", caption: "Dyeing machines", sort: 5 },
        { id: "g3", album: "Production", image: "assets/images/gallery/gallery-3.jpg", alt: "Sample washing machines used for fabric testing", caption: "Sample washing machines", sort: 6 },
        { id: "g6", album: "Yarn", image: "assets/images/gallery/gallery-6.jpg", alt: "White fabric running on the finishing line", caption: "White fabric on the line", sort: 7 },
        { id: "g10", album: "Factory", image: "assets/images/gallery/gallery-10.jpg", alt: "Finishing floor aisle at the Narayanganj mill", caption: "Finishing floor", sort: 8 },
        { id: "g13", album: "Yarn", image: "assets/images/gallery/gallery-13.jpg", alt: "Printed fabric on the finishing line", caption: "Printed fabric on the finishing line", sort: 9 },
        { id: "g5", album: "Production", image: "assets/images/gallery/gallery-5.jpg", alt: "Dyeing machines on the wet-processing floor", caption: "Dyeing machines", sort: 10 },
        { id: "g-line", album: "Production", image: "assets/images/products/product-2.jpg", alt: "Production line on the mill floor", caption: "Production line", sort: 11 },
        { id: "g-loom", album: "Weaving", image: "assets/images/products/product-0.jpg", alt: "Weaving loom running a cotton warp", caption: "Weaving loom", sort: 12 }
      ],
      news: [
        { id: "efficiency", title: "Mill Efficiency Upgrade Completes Phase One", slug: "mill-efficiency-upgrade", date: "2026-08-12", lead: "Loom monitoring and inspection checkpoints for more consistent woven cotton lots.", body: "Loom monitoring, in-process inspection, and clearer lot identification across the Narayanganj weaving and finishing floors.", cover: "assets/images/products/product-0.jpg", status: "Published" },
        { id: "cotton-sourcing", title: "Responsible Cotton Sourcing Update", slug: "responsible-cotton-sourcing", date: "2026-06-04", lead: "Traceable cotton inputs and dyehouse water stewardship for export programs.", body: "Traceable cotton inputs and dyehouse water stewardship for export programs.", cover: "assets/images/news/news-0.jpg", status: "Published" },
        { id: "export-milestone", title: "Export Milestone Across 18 Markets", slug: "export-milestone", date: "2026-03-18", lead: "Woven cotton shipments expand with consistent greige and finished programs.", body: "Woven cotton shipments expand with consistent greige and finished programs.", cover: "assets/images/news/news-2.jpg", status: "Published" },
        { id: "lab-shade", title: "In-House Lab Shade Matching Upgrade", slug: "lab-shade-matching", date: "2025-11-22", lead: "Expanded physical and chemical testing for yarn, greige, and finished fabric.", body: "Expanded physical and chemical testing for yarn, greige, and finished fabric.", cover: "assets/images/gallery/gallery-3.jpg", status: "Published" },
        { id: "airjet", title: "Additional Air-Jet Capacity Online", slug: "air-jet-capacity", date: "2025-09-09", lead: "New weaving capacity supports wider greige widths for apparel buyers.", body: "New weaving capacity supports wider greige widths for apparel buyers.", cover: "assets/images/products/product-1.jpg", status: "Published" },
        { id: "buyer-visits", title: "Spring Buyer Visits at Narayanganj Mill", slug: "buyer-visits", date: "2025-05-15", lead: "Facility tours focused on spinning-to-finishing traceability for woven cotton.", body: "Facility tours focused on spinning-to-finishing traceability for woven cotton.", cover: "assets/images/news/news-1.jpg", status: "Published" },
        { id: "etp", title: "ETP Capacity Reinforcement Complete", slug: "etp-capacity", date: "2024-12-02", lead: "Effluent treatment upgrades sized for continuous dyehouse operation.", body: "Effluent treatment upgrades sized for continuous dyehouse operation.", cover: "assets/images/gallery/gallery-1.jpg", status: "Published" },
        { id: "poplin-spec", title: "Shirting Poplin Spec Tightening", slug: "poplin-spec", date: "2024-08-21", lead: "Closer reed/pick tolerances for corporate shirting and uniform programs.", body: "Closer reed/pick tolerances for corporate shirting and uniform programs.", cover: "assets/images/gallery/gallery-2.jpg", status: "Published" },
        { id: "combed-yarn", title: "Combed Yarn Count Range Expanded", slug: "combed-yarn-range", date: "2024-02-10", lead: "Ring-spun combed counts Ne 20–40 for weaving and downstream finishing.", body: "Ring-spun combed counts Ne 20–40 for weaving and downstream finishing.", cover: "assets/images/products/product-0.jpg", status: "Published" }
      ],
      careers: {
        jobs: [
          { id: "qi", title: "Quality Inspector — Woven Fabric", location: "Narayanganj, Bangladesh", summary: "Inspect greige and finished lots on the weaving and finishing floors.", description: "Full-time role in the Quality Department. The work is inspection of greige and finished woven lots, with records that stay with the batch.", status: "Open", image: "assets/images/news/news-2.jpg" },
          { id: "loom", title: "Loom Technician", location: "Narayanganj, Bangladesh", summary: "Air-jet and rapier weaving.", description: "Full-time weaving role for air-jet and rapier looms at the Narayanganj mill.", status: "Open", image: "assets/images/products/product-0.jpg" },
          { id: "merch", title: "Merchandising Coordinator", location: "Dhaka, Bangladesh", summary: "Sampling and buyer follow-up.", description: "Commercial desk in Dhaka for sampling and buyer follow-up on woven programs.", status: "Open", image: "assets/images/admin/career-samples.jpg" },
          { id: "dye", title: "Dyehouse Supervisor", location: "Narayanganj, Bangladesh", summary: "Reactive and pigment programs.", description: "Supervises reactive and pigment dyeing on the Narayanganj wet-processing floor.", status: "Open", image: "assets/images/gallery/gallery-5.jpg" },
          { id: "spin", title: "Spinning Shift Lead", location: "Narayanganj, Bangladesh", summary: "Ring-spun combed and carded yarn.", description: "Leads a spinning shift for weaving-grade cotton yarn.", status: "Open", image: "assets/images/gallery/gallery-7.jpg" }
        ],
        applications: []
      },
      inquiries: [
        { id: "inq-1", date: "2026-09-08", company: "Nordic Home Textiles", interest: "Finished fabric", status: "New", message: "Request for finished fabric program details.", image: "assets/images/products/product-3.jpg" },
        { id: "inq-2", date: "2026-09-06", company: "Delta Apparel Co.", interest: "Yarn", status: "In progress", message: "Yarn count and lead-time question.", image: "assets/images/gallery/gallery-7.jpg" },
        { id: "inq-3", date: "2026-09-02", company: "Eastern Traders", interest: "Greige woven", status: "Closed", message: "Greige woven inquiry, closed after reply.", image: "assets/images/products/product-1.jpg" }
      ],
      media: [
        { id: "m-loom", file: "assets/images/products/product-0.jpg", alt: "Weaving loom at the Islam Textile mill in Narayanganj", caption: "Weaving loom" },
        { id: "m-warp", file: "assets/images/products/product-1.jpg", alt: "Warp beams feeding a weaving loom", caption: "Warp beams" },
        { id: "m-line", file: "assets/images/products/product-2.jpg", alt: "Production line on the mill floor", caption: "Production line" },
        { id: "m-yarn", file: "assets/images/process/step-06.jpg", alt: "Yarn rolls on racking at the mill", caption: "Yarn rolls" },
        { id: "m-step", file: "assets/images/process/step-05.jpg", alt: "Manufacturing floor at the mill", caption: "Process floor" },
        { id: "m0", file: "assets/images/gallery/gallery-0.jpg", alt: "Dyeing machines on the wet-processing floor", caption: "Dyeing machines" },
        { id: "m1", file: "assets/images/gallery/gallery-1.jpg", alt: "Effluent treatment plant at the Narayanganj campus", caption: "ETP" },
        { id: "m2", file: "assets/images/gallery/gallery-2.jpg", alt: "Fabric on the finishing line", caption: "Finishing line" },
        { id: "m3", file: "assets/images/gallery/gallery-3.jpg", alt: "Sample washing machines used for fabric testing", caption: "Sample washing" },
        { id: "m4", file: "assets/images/gallery/gallery-4.jpg", alt: "Color dispensing laboratory", caption: "Laboratory" },
        { id: "m5", file: "assets/images/gallery/gallery-5.jpg", alt: "Dyeing machines on the wet-processing floor", caption: "Dyeing floor" },
        { id: "m6", file: "assets/images/gallery/gallery-6.jpg", alt: "White fabric running on the finishing line", caption: "White fabric" },
        { id: "m7", file: "assets/images/gallery/gallery-7.jpg", alt: "Yarn rolls stored on racking", caption: "Yarn store" },
        { id: "m8", file: "assets/images/gallery/gallery-8.jpg", alt: "White fabric running through a finishing range", caption: "Finishing range" },
        { id: "m10", file: "assets/images/gallery/gallery-10.jpg", alt: "Finishing floor aisle at the Narayanganj mill", caption: "Finishing floor" },
        { id: "m13", file: "assets/images/gallery/gallery-13.jpg", alt: "Folded finished fabric beside the finishing line", caption: "Folded fabric" },
        { id: "m-news0", file: "assets/images/news/news-0.jpg", alt: "Effluent treatment tanks at the mill", caption: "ETP tanks" },
        { id: "m-news1", file: "assets/images/news/news-1.jpg", alt: "Printing and inspection line", caption: "Inspection line" },
        { id: "m-news2", file: "assets/images/news/news-2.jpg", alt: "Fabric checked on a lighted inspection table", caption: "Inspection table" },
        { id: "m-spin", file: "assets/images/facilities/dept-spinning.jpg", alt: "Dyeing machines on the wet-processing floor", caption: "Wet processing" },
        { id: "m-util", file: "assets/images/facilities/dept-processing.jpg", alt: "Mill utility pumps and air systems", caption: "Utilities" },
        { id: "m-etp", file: "assets/images/facilities/dept-etp.jpg", alt: "Effluent treatment plant at the Narayanganj campus", caption: "ETP plant" }
      ]
    };
  }

  function read() {
    try {
      var raw = localStorage.getItem(KEY);
      if (!raw) return null;
      return JSON.parse(raw);
    } catch (err) {
      return null;
    }
  }

  function write(data) {
    localStorage.setItem(KEY, JSON.stringify(data));
  }

  function ensure() {
    var data = read();
    if (!data || !data.settings || !data.home) {
      data = seed();
      write(data);
    }
    if (data.settings && !data.settings.officePin && !data.settings.millPin) {
      try {
        var pins = JSON.parse(localStorage.getItem("it-contact-pins") || "{}");
        if (pins.office || pins.mill) {
          data.settings.officePin = pins.office || "";
          data.settings.millPin = pins.mill || "";
          write(data);
        }
      } catch (err) {}
    }
    if (data.home && data.home.insights) {
      data.home.insights.forEach(function (item) {
        if (item && item.title === "Sustainability" && !item.image) item.image = "assets/images/gallery/gallery-1.jpg";
      });
    }
    if (data.categories) {
      var categoryShots = {
        yarn: "assets/images/gallery/gallery-7.jpg",
        woven: "assets/images/products/product-0.jpg",
        finished: "assets/images/products/product-3.jpg"
      };
      data.categories.forEach(function (row) {
        if (row && !row.image && categoryShots[row.id]) row.image = categoryShots[row.id];
      });
    }
    if (data.inquiries) {
      var inquiryShots = {
        "inq-1": "assets/images/products/product-3.jpg",
        "inq-2": "assets/images/gallery/gallery-7.jpg",
        "inq-3": "assets/images/products/product-1.jpg"
      };
      data.inquiries.forEach(function (row) {
        if (row && !row.image && inquiryShots[row.id]) row.image = inquiryShots[row.id];
      });
    }
    if (data.careers && data.careers.jobs) {
      var jobShots = seed().careers.jobs;
      jobShots.forEach(function (job) {
        var found = data.careers.jobs.filter(function (row) { return row.id === job.id; })[0];
        if (!found) data.careers.jobs.push(job);
        else if (!found.image) found.image = job.image;
      });
    }
    write(data);
    return data;
  }

  window.ITAdmin = {
    key: KEY,
    seed: function () { return seed(); },
    all: function () { return clone(ensure()); },
    get: function (module) {
      var data = ensure();
      var value = data[module];
      if (value == null) return value;
      return clone(value);
    },
    set: function (module, value) {
      var data = ensure();
      data[module] = value;
      write(data);
      return clone(value);
    },
    reset: function (module) {
      var data = ensure();
      var fresh = seed();
      if (module) data[module] = fresh[module];
      else data = fresh;
      write(data);
      return clone(module ? data[module] : data);
    }
  };
})();
