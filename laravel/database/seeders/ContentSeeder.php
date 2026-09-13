<?php

namespace Database\Seeders;

use App\Models\CareerJob;
use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\PageField;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Support\Mill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->media();
        $this->settings();
        $this->pages();
        $this->catalog();
        $this->gallery();
        $this->news();
        $this->careers();
    }

    private function media(): void
    {
        $folder = MediaFolder::query()->firstOrCreate(['name' => 'Mill'], ['sort' => 1]);
        $files = [
            ['assets/images/products/product-0.jpg', 'Weaving loom at the Islam Textile mill in Narayanganj', 'Weaving loom'],
            ['assets/images/products/product-1.jpg', 'Warp beams feeding a weaving loom', 'Warp beams'],
            ['assets/images/products/product-2.jpg', 'Production line on the mill floor', 'Production line'],
            ['assets/images/products/product-3.jpg', 'Finished fabric', 'Finished fabric'],
            ['assets/images/process/step-06.jpg', 'Yarn rolls on racking at the mill', 'Yarn rolls'],
            ['assets/images/process/step-05.jpg', 'Manufacturing floor at the mill', 'Process floor'],
            ['assets/images/gallery/gallery-0.jpg', 'Dyeing machines on the wet-processing floor', 'Dyeing machines'],
            ['assets/images/gallery/gallery-1.jpg', 'Effluent treatment plant at the Narayanganj campus', 'ETP'],
            ['assets/images/gallery/gallery-2.jpg', 'Fabric on the finishing line', 'Finishing line'],
            ['assets/images/gallery/gallery-3.jpg', 'Sample washing machines used for fabric testing', 'Sample washing'],
            ['assets/images/gallery/gallery-4.jpg', 'Color dispensing laboratory', 'Laboratory'],
            ['assets/images/gallery/gallery-5.jpg', 'Dyeing machines on the wet-processing floor', 'Dyeing floor'],
            ['assets/images/gallery/gallery-6.jpg', 'White fabric running on the finishing line', 'White fabric'],
            ['assets/images/gallery/gallery-7.jpg', 'Yarn rolls stored on racking', 'Yarn store'],
            ['assets/images/gallery/gallery-8.jpg', 'White fabric running through a finishing range', 'Finishing range'],
            ['assets/images/gallery/gallery-10.jpg', 'Finishing floor aisle at the Narayanganj mill', 'Finishing floor'],
            ['assets/images/gallery/gallery-13.jpg', 'Folded finished fabric beside the finishing line', 'Folded fabric'],
            ['assets/images/news/news-0.jpg', 'Effluent treatment tanks at the mill', 'ETP tanks'],
            ['assets/images/news/news-1.jpg', 'Printing and inspection line', 'Inspection line'],
            ['assets/images/news/news-2.jpg', 'Fabric checked on a lighted inspection table', 'Inspection table'],
            ['assets/images/facilities/dept-spinning.jpg', 'Dyeing machines on the wet-processing floor', 'Wet processing'],
            ['assets/images/facilities/dept-processing.jpg', 'Mill utility pumps and air systems', 'Utilities'],
            ['assets/images/facilities/dept-etp.jpg', 'Effluent treatment plant at the Narayanganj campus', 'ETP plant'],
            ['assets/images/brand/logo-01-monogram-it-ribbon.png', 'Islam Textile monogram', 'Logo'],
            ['assets/images/brand/favicon.png', 'Islam Textile favicon', 'Favicon'],
            ['assets/images/admin/career-samples.jpg', 'Sampling desk', 'Career samples'],
        ];

        foreach ($files as [$file, $alt, $caption]) {
            $path = Mill::copyPublicImage($file);
            if (! $path) {
                continue;
            }
            $absolute = public_path('assets/images/'.preg_replace('#^assets/images/#', '', $file));
            $size = @getimagesize($absolute) ?: [null, null];
            Media::query()->firstOrCreate(['path' => $path], [
                'media_folder_id' => $folder->id,
                'disk' => 'public',
                'alt' => $alt,
                'caption' => $caption,
                'mime' => is_file($absolute) ? mime_content_type($absolute) : null,
                'width' => $size[0],
                'height' => $size[1],
                'bytes' => is_file($absolute) ? filesize($absolute) : null,
            ]);
        }
    }

    private function settings(): void
    {
        $logo = Mill::copyPublicImage('assets/images/brand/logo-01-monogram-it-ribbon.png');
        $favicon = Mill::copyPublicImage('assets/images/brand/favicon.png');
        $og = Mill::copyPublicImage('assets/images/products/product-0.jpg');

        $setting = Setting::query()->first();
        if (! $setting) {
            $setting = Setting::query()->create([
                'site_name' => 'Islam Textile',
                'tagline' => 'Weaving Tradition, Ensuring Quality',
                'website' => 'www.islamtextile.com',
                'canonical_base' => 'https://www.islamtextile.com',
                'footer_blurb' => 'Premium cotton yarn, woven fabric, and finishing from Dhaka and Narayanganj, Bangladesh.',
                'hours' => 'Sunday–Thursday, 9:00–18:00 (BST)',
                'office_address' => '[Street / Area], Dhaka, Bangladesh',
                'mill_address' => '[Factory Road], Narayanganj, Bangladesh',
                'phone' => '+880 1XXX-XXXXXX',
                'email' => 'info@islamtextile.com',
                'office_pin' => '',
                'mill_pin' => '',
                'meta_title' => 'Islam Textile | Premium Cotton Woven Textiles',
                'meta_description' => 'Cotton yarn, woven fabric, and dyeing & finishing from Bangladesh.',
                'og_image' => $og,
                'logo' => $logo,
                'favicon' => $favicon,
                'theme_primary' => '#004d40',
                'theme_deep' => '#003d33',
                'theme_accent' => '#548c84',
                'theme_surface' => '#f4f7f6',
                'maintenance' => false,
            ]);
        }

        foreach (['Facebook', 'Instagram', 'LinkedIn', 'YouTube', 'WhatsApp', 'X'] as $index => $label) {
            SocialLink::query()->firstOrCreate(
                ['setting_id' => $setting->id, 'label' => $label],
                ['url' => '', 'sort' => $index + 1]
            );
        }
    }

    private function pages(): void
    {
        $home = $this->page('home', [
            'title' => 'Home',
            'status' => 'published',
            'meta_title' => 'Islam Textile | Premium Cotton Woven Textiles — Bangladesh',
            'meta_description' => 'Islam Textile — cotton yarn, woven fabric, and dyeing & finishing from Dhaka and Narayanganj, Bangladesh. Weaving tradition, ensuring quality.',
        ], [
            'hero_image' => $this->img('assets/images/products/product-0.jpg'),
            'hero_headline' => 'Premium Cotton. Woven to Perfection.',
            'hero_lead' => 'Reliable quality, sustainable practices, and on-time delivery you can count on.',
            'hero_primary_label' => 'Request Quote',
            'hero_primary_href' => '/contact',
            'hero_secondary_label' => 'Explore Products',
            'hero_secondary_href' => '/products',
            'why_title' => 'Trusted by buyers who need consistency',
            'why_lead' => 'Focused on cotton woven textiles — not apparel retail — so quality, capacity, and delivery stay aligned for export programs.',
            'integration_title' => 'From yarn to finished fabric',
            'integration_lead' => 'One coordinated mill pathway — fewer handoffs, clearer accountability, and batch identity from the first bale to the packed roll.',
            'facilities_title' => 'Built for reliable bulk production',
            'facilities_lead' => 'One Narayanganj campus for spinning, weaving, and wet processing — with Dhaka program coordination and Chattogram export logistics.',
            'facilities_image' => $this->img('assets/images/products/product-0.jpg'),
            'quality_title' => 'Standards woven into every stage',
            'quality_lead' => 'From yarn evenness to packed-roll inspection — quality is a mill culture, not a last-minute gate.',
            'markets_title' => 'Bangladesh-made cotton for global programs',
            'markets_lead' => 'Greige and finished woven cotton for buyers who need the same construction, hand, and shade on the next order.',
            'gallery_title' => 'Inside the mill',
            'gallery_lead' => 'Documented floors at Narayanganj — weaving, dyeing, finishing, and utilities — without portraits.',
            'careers_title' => 'Build your career in textiles',
            'careers_lead' => 'Join a Bangladesh cotton mill team focused on quality manufacturing, continuous improvement, and safe operations in Narayanganj.',
            'careers_button' => 'View Careers',
            'cta_title' => "Let's Build Something Great Together",
            'cta_lead' => 'Share constructions, finishes, and volumes — our Dhaka commercial team will respond with next steps.',
        ]);

        $this->blocks($home, 'stats', [
            ['value' => '500', 'suffix' => '+', 'title' => 'Looms Installed'],
            ['value' => '18000', 'suffix' => '+', 'title' => 'MT Annual Capacity'],
            ['value' => '25', 'suffix' => '+', 'title' => 'Countries Served'],
            ['value' => '800', 'suffix' => '+', 'title' => 'Skilled Employees'],
        ]);
        $this->blocks($home, 'products', [
            ['title' => 'Yarn', 'text' => 'High-quality cotton yarns for superior strength and consistent performance.', 'href' => '/products#yarn'],
            ['title' => 'Woven Fabric', 'text' => 'Durable, versatile fabrics crafted with precision and care.', 'href' => '/products/woven-fabric'],
            ['title' => 'Finished Fabric', 'text' => 'Ready-to-use fabrics with quality finishing for your exact needs.', 'href' => '/products#finishing'],
        ]);
        $this->blocks($home, 'insights', [
            ['title' => 'From Fiber to Finish', 'text' => 'An integrated cotton pathway — spinning, weaving, and finishing — under one quality system in Bangladesh.', 'href' => '/process', 'link_label' => 'Discover Our Process', 'image' => $this->img('assets/images/process/step-06.jpg')],
            ['title' => 'Sustainability', 'text' => 'Responsible water, energy, and cotton practices across our Narayanganj mill operations.', 'href' => '/sustainability', 'link_label' => 'Our Commitment', 'image' => $this->img('assets/images/gallery/gallery-1.jpg')],
            ['title' => 'Latest News', 'text' => '', 'href' => '/news', 'link_label' => 'View All News', 'image' => $this->img('assets/images/gallery/gallery-8.jpg')],
        ]);
        $this->blocks($home, 'why_items', [
            ['title' => 'Quality First', 'text' => 'In-process checks and in-house lab testing for construction, shade, and handfeel.'],
            ['title' => 'Scalable Capacity', 'text' => 'Spinning, weaving, and finishing sized for repeat bulk orders and seasonal peaks.'],
            ['title' => 'On-Time Delivery', 'text' => 'Program planning from Dhaka with mill execution in Narayanganj and Chattogram logistics.'],
            ['title' => 'Export Ready', 'text' => 'Buyer-facing specs, packing standards, and documentation for global apparel and home textile markets.'],
        ]);
        $this->blocks($home, 'steps', [
            ['title' => 'Fiber', 'text' => 'Cotton opened and carded for length and cleanliness.', 'meta' => 'Opening · Carding'],
            ['title' => 'Spinning', 'text' => 'Ring-spun yarn with controlled twist, evenness, and strength.', 'meta' => 'Ne 20–40'],
            ['title' => 'Weaving', 'text' => 'Greige woven to spec on air-jet and rapier looms, then inspected.', 'meta' => 'Air-jet · Rapier'],
            ['title' => 'Dyeing', 'text' => 'Reactive and pigment shades held to approved lab dips.', 'meta' => 'Shade control'],
            ['title' => 'Finishing', 'text' => 'Mercerize, sanforize, and soften for hand and shrinkage.', 'meta' => 'Mercer · Sanfor'],
            ['title' => 'Dispatch', 'text' => 'Lab-checked rolls packed with batch documents for export.', 'meta' => 'Export packing'],
        ]);
        $this->blocks($home, 'facility_bullets', [
            ['title' => 'Continuous utilities', 'text' => 'Boiler, compressed air, and power backup sized for 24/7 running.'],
            ['title' => 'Export packing', 'text' => 'Inspected rolls and buyer packing specs before dispatch.'],
            ['title' => 'Dhaka buyer desk', 'text' => 'Commercial follow-up while the mill holds the lot.'],
        ]);
        $this->blocks($home, 'quality_points', [
            ['title' => 'In-house lab', 'text' => 'Tensile, GSM, colorfastness, yarn U%, and shade ΔE before shipment.'],
            ['title' => 'Process control', 'text' => 'Yarn check, loom-side inspection, and 4-point fabric mapping on the lot.'],
            ['title' => 'Buyer specs', 'text' => 'Construction, hand, and shade panels held to apparel and home textile programs.'],
            ['title' => 'Audit ready', 'text' => 'Test reports and packing documents available for buyer and audit review.'],
        ]);
        $this->blocks($home, 'markets', [
            ['title' => 'Apparel', 'text' => 'Poplin and oxford for shirting, uniforms, and linings.'],
            ['title' => 'Home textile', 'text' => 'Sheeting and soft furnishings in greige or finished cotton.'],
            ['title' => 'Workwear', 'text' => 'Durable plain and twill built for repeat bulk programs.'],
            ['title' => 'Industrial', 'text' => 'Spec-driven width, GSM, and construction to the buyer brief.'],
        ]);
        $this->blocks($home, 'gallery_strip', [
            ['title' => 'Weaving', 'image' => $this->img('assets/images/products/product-1.jpg'), 'text' => 'Weaving loom with warp beams at Islam Textile', 'href' => '/gallery'],
            ['title' => 'Finishing', 'image' => $this->img('assets/images/gallery/gallery-2.jpg'), 'text' => 'Fabric on the finishing line', 'href' => '/gallery'],
            ['title' => 'Dyeing', 'image' => $this->img('assets/images/facilities/dept-spinning.jpg'), 'text' => 'Dyeing machines on the wet-processing floor', 'href' => '/gallery'],
            ['title' => 'ETP', 'image' => $this->img('assets/images/gallery/gallery-1.jpg'), 'text' => 'Effluent treatment plant at the Narayanganj campus', 'href' => '/gallery'],
        ]);
        $this->blocks($home, 'facility_figures', [
            ['value' => '120000', 'suffix' => '+', 'title' => 'Spindles'],
            ['value' => '480', 'suffix' => '+', 'title' => 'Looms'],
            ['value' => '1200', 'suffix' => '+', 'title' => 'Workforce'],
            ['value' => '25', 'suffix' => 'M+', 'title' => 'Yards / Year'],
        ]);

        $about = $this->page('about', [
            'title' => 'About Us',
            'image' => $this->img('assets/images/gallery/gallery-2.jpg'),
            'meta_title' => 'About Us | Islam Textile',
            'meta_description' => 'A cotton mill for export buyers — yarn, woven fabric, and dyeing & finishing from Dhaka and Narayanganj.',
        ], [
            'hero_lead' => 'A cotton mill for export buyers — yarn, woven fabric, and dyeing & finishing from Dhaka and Narayanganj, not apparel retail.',
            'heritage_title' => 'Three decades of cotton expertise',
            'heritage_text' => 'Founded in Narayanganj, Islam Textile grew from a weaving shed into an integrated mill — spinning, weaving, and dyeing & finishing under one quality system.',
            'heritage_more' => 'The work stays on cotton woven textiles. Buyers get one accountable partner, lot identity from fiber to packed roll, and lead times they can plan around.',
            'vision' => 'Bangladesh’s trusted partner for cotton yarn and woven fabric — known for consistency and responsible manufacturing.',
            'mission' => 'Export-ready cotton through integrated spinning, weaving, and finishing — with clear communication on every program.',
            'values' => "Quality at every gate\nLong-term buyer partnerships\nContinuous mill improvement\nRespect for people and the mill",
        ]);
        $this->blocks($about, 'timeline', [
            ['year' => '1990', 'title' => 'Founded', 'text' => 'Weaving shed established in Narayanganj’s textile belt.'],
            ['year' => '2002', 'title' => 'Spinning added', 'text' => 'In-house ring spinning for weaving-grade cotton yarn.'],
            ['year' => '2010', 'title' => 'Air-jet expansion', 'text' => 'Loom hall commissioned for export greige capacity.'],
            ['year' => '2016', 'title' => 'Dyehouse online', 'text' => 'Reactive dyeing and finishing for export-ready fabric.'],
            ['year' => '2024', 'title' => 'Global reach', 'text' => 'Programs for buyers across 25+ countries.'],
        ]);
        $this->blocks($about, 'leaders', [
            ['initials' => 'RI', 'title' => 'Md. Rafiqul Islam', 'role' => 'Chairman', 'text' => 'Steward of the growth from weaving shed to integrated mill.'],
            ['initials' => 'NJ', 'title' => 'Nusrat Jahan', 'role' => 'Managing Director', 'text' => 'Leads buyer partnerships, commercial strategy, and export programs.'],
            ['initials' => 'TA', 'title' => 'Tanvir Ahmed', 'role' => 'Director, Operations', 'text' => 'Oversees spinning, weaving, and finishing capacity at Narayanganj.'],
            ['initials' => 'FR', 'title' => 'Farhana Rahman', 'role' => 'Director, Quality', 'text' => 'Owns the laboratory, lot records, and pre-dispatch assurance.'],
        ]);

        $process = $this->page('process', [
            'eyebrow' => 'How we make',
            'title' => 'Our manufacturing process',
            'lead' => 'Six integrated stages — from yarn on the rack to inspected, export-ready woven fabric at our Narayanganj mill. Every lot keeps its batch identity through to the packed roll.',
            'image' => $this->img('assets/images/process/step-05.jpg'),
        ], [
            'quote' => 'Quality is not a final gate — it is built into every stage from fiber blend to packed roll.',
        ]);
        $this->blocks($process, 'stages', [
            ['title' => 'Yarn preparation', 'text' => 'Ring-spun yarn is racked by lot and checked for count and cleanliness before it is beamed.', 'image' => $this->img('assets/images/process/step-06.jpg')],
            ['title' => 'Warping', 'text' => 'Yarn is wound onto warp beams and sized before it goes on the loom.', 'image' => $this->img('assets/images/products/product-1.jpg')],
            ['title' => 'Weaving', 'text' => 'Warping, sizing, and weaving on air-jet and rapier looms — poplin, twill, oxford, and plain.', 'image' => $this->img('assets/images/products/product-0.jpg')],
            ['title' => 'Inspection', 'text' => 'Greige is mapped on a lighted table before it moves to dyeing or packing.', 'image' => $this->img('assets/images/news/news-2.jpg')],
            ['title' => 'Dyeing & finishing', 'text' => 'Shade, hand, and shrinkage are held to the approved panel.', 'image' => $this->img('assets/images/gallery/gallery-5.jpg')],
            ['title' => 'Final QA & dispatch', 'text' => 'Lab-checked rolls leave with batch documents.', 'image' => $this->img('assets/images/gallery/gallery-8.jpg')],
        ]);

        $facilities = $this->page('facilities', [
            'title' => 'Built on one campus. Sized for export programs.',
            'lead' => 'Spinning, weaving, and wet processing in Narayanganj — with a Dhaka desk for buyer programs and Chattogram for export logistics.',
            'image' => $this->img('assets/images/products/product-0.jpg'),
        ]);
        $this->blocks($facilities, 'figures', [
            ['value' => '120000', 'suffix' => '+', 'title' => 'Spindles', 'meta' => 'Ring spinning'],
            ['value' => '480', 'suffix' => '+', 'title' => 'Looms', 'meta' => 'Air-jet and rapier'],
            ['value' => '1200', 'suffix' => '+', 'title' => 'Workforce', 'meta' => 'Narayanganj mill'],
            ['value' => '25', 'suffix' => 'M+', 'title' => 'Yards / Year', 'meta' => 'Woven output'],
        ]);
        $this->blocks($facilities, 'campus', [
            ['title' => 'Narayanganj campus', 'text' => 'Primary mill in Bangladesh’s textile belt, with Chattogram access for export shipment.'],
            ['title' => '24/7 utilities', 'text' => 'Boiler, compressed air, and power backup sized for continuous spinning, weaving, and wet processing.'],
            ['title' => 'Export packing', 'text' => 'Inspected rolls packed to buyer specification before dispatch.'],
            ['title' => 'Dhaka buyer desk', 'text' => 'Commercial follow-up in Dhaka while the mill holds the lot.'],
        ]);
        $this->blocks($facilities, 'departments', [
            ['title' => 'Spinning', 'text' => 'Ring spinning for weaving-grade cotton yarn.'],
            ['title' => 'Weaving', 'text' => 'Air-jet and rapier looms for greige woven cotton.'],
            ['title' => 'Wet processing', 'text' => 'Dyeing and finishing on the same campus.'],
            ['title' => 'Laboratory', 'text' => 'In-house checks before a lot is packed.'],
            ['title' => 'Warehouse', 'text' => 'Inspected rolls held for dispatch.'],
            ['title' => 'ETP', 'text' => 'Effluent treated before discharge.'],
        ]);
        $this->blocks($facilities, 'dept_cards', [
            ['title' => 'Weaving', 'image' => $this->img('assets/images/products/product-1.jpg'), 'text' => 'Weaving loom with warp beams'],
            ['title' => 'Dyeing', 'image' => $this->img('assets/images/facilities/dept-spinning.jpg'), 'text' => 'Dyeing machines on the wet-processing floor'],
            ['title' => 'Finishing', 'image' => $this->img('assets/images/gallery/gallery-2.jpg'), 'text' => 'Fabric on the finishing line'],
            ['title' => 'Color lab', 'image' => $this->img('assets/images/gallery/gallery-4.jpg'), 'text' => 'Color dispensing laboratory'],
            ['title' => 'Utilities', 'image' => $this->img('assets/images/facilities/dept-processing.jpg'), 'text' => 'Mill utility pumps and air systems'],
            ['title' => 'ETP', 'image' => $this->img('assets/images/facilities/dept-etp.jpg'), 'text' => 'Effluent treatment plant at the Narayanganj campus'],
        ]);

        $quality = $this->page('quality', [
            'title' => 'Quality and certifications',
            'line' => 'Quality is woven into the process — not added at the dock.',
            'lead' => 'From yarn evenness to packed-roll inspection. Shade, strength, and documents stay with the shipment out of Narayanganj.',
            'image' => $this->img('assets/images/gallery/gallery-2.jpg'),
        ]);
        $this->blocks($quality, 'points', [
            ['title' => 'Yarn check', 'text' => 'Evenness and strength before the beam'],
            ['title' => 'Loom-side', 'text' => 'Greige defects caught on the weaving floor'],
            ['title' => '4-point', 'text' => 'Fabric mapped before dyeing or packing'],
            ['title' => 'Laboratory', 'text' => 'GSM, strength, colorfastness, and shade'],
            ['title' => 'Shade', 'text' => 'Held to the approved lab dip'],
            ['title' => 'Dispatch', 'text' => 'Rolls and documents leave together'],
        ]);
        $this->blocks($quality, 'documents', [
            ['title' => 'Test report', 'note' => 'Buyer-request document — not an earned certificate'],
            ['title' => 'Shade panel', 'note' => 'Buyer-request document — not an earned certificate'],
            ['title' => 'Inspection map', 'note' => 'Buyer-request document — not an earned certificate'],
            ['title' => 'Packing list', 'note' => 'Buyer-request document — not an earned certificate'],
            ['title' => 'Quality notes', 'note' => 'Buyer-request document — not an earned certificate'],
            ['title' => 'Audit pack', 'note' => 'Buyer-request document — not an earned certificate'],
        ]);

        $sustainability = $this->page('sustainability', [
            'title' => 'Sustainability',
            'lead' => 'Responsible water, energy, and cotton practices across our Narayanganj mill operations.',
            'line' => 'Responsible operations. Measured at the mill.',
            'commitment_title' => 'Measurable progress. Continuous improvement.',
            'body' => 'Targets are set each year for water, energy, and chemical compliance. The figures that apply to a program are shared when a buyer requests the profile.',
            'image' => $this->img('assets/images/gallery/gallery-1.jpg'),
        ]);
        $this->blocks($sustainability, 'pillars', [
            ['title' => 'Water care', 'text' => 'Effluent is treated before discharge. Rinsing and liquor ratio are managed to cut freshwater use per meter.', 'href' => '/contact', 'link_label' => 'Ask about water data'],
            ['title' => 'Energy', 'text' => 'Heat is recovered on the finishing line, and loom and utility loads are tracked by shift.', 'href' => '/contact', 'link_label' => 'Energy overview'],
            ['title' => 'Chemistry', 'text' => 'Dyestuffs and auxiliaries are held to an approved list, with MRSL screening and safe-handling practice.', 'href' => '/quality', 'link_label' => 'Quality and chemistry'],
            ['title' => 'Waste reduction', 'text' => 'Yarn and fabric scrap are separated, and export packing is tightened so less material leaves as waste.', 'href' => '/facilities', 'link_label' => 'See facilities'],
        ]);

        $this->page('contact', [
            'title' => 'Contact & Request Quote',
            'lead' => 'Tell us about your yarn or woven fabric program — constructions, finishes, volumes, and target markets.',
            'card_title' => 'Reach the mill',
            'card_lead' => 'The Dhaka desk handles buyer programs. Mill visits in Narayanganj are by appointment.',
            'image' => $this->img('assets/images/gallery/gallery-2.jpg'),
        ]);

        $this->page('privacy', [
            'title' => 'Privacy Policy',
            'updated_on' => '12 September 2026',
            'body' => "Islam Textile respects your privacy. This page explains how information submitted through inquiry or career forms on this website is used to respond to your request.\n\n1. Information we collect\nWe collect contact details and project information you voluntarily provide, plus limited technical logs used for security.\n\n2. How we use data\nInformation is used to handle the request you sent: quotes, applications, support, site security, and legal obligations under Bangladesh law where applicable.\n\n3. Sharing\nWe do not sell form data. It is shared only as needed to respond to the request or where the law requires it.\n\n4. Retention\nInquiry records are kept only as long as needed to handle the request and any follow-up.\n\n5. Contact\nQuestions about this page: info@islamtextile.com",
        ]);

        $this->page('terms', [
            'title' => 'Terms of Use',
            'lead' => 'These terms govern use of the Islam Textile corporate website. By using this website you agree to these terms.',
            'body' => "1. Acceptance of Terms\nBy using this website you agree to these terms. Content is provided for general business information about Islam Textile’s cotton yarn, woven fabric, and finishing capabilities in Bangladesh.\n\n2. Information Accuracy\nSpecifications, capacities, and imagery may be illustrative placeholders until confirmed in a formal quotation from our Dhaka commercial team.\n\n3. Intellectual Property\nSite design, text, photography, and brand marks belong to Islam Textile unless otherwise noted. Do not reproduce without written permission.\n\n4. Inquiries\nSubmitting a form does not create a contract. A quotation is issued separately by the commercial team.\n\n5. Limitation of Liability\nThe website is provided as general information. Confirm specifications in a formal quotation.\n\n6. Contact\ninfo@islamtextile.com",
        ]);

        $this->page('success', [
            'title' => 'Thank You',
            'body' => 'Your inquiry has been recorded. Our Dhaka team will receive this and respond with next steps.',
        ]);

        $this->page('products', [
            'eyebrow' => 'Our products',
            'title' => 'From yarn to finished fabric',
            'lead' => 'Integrated cotton textile offerings — from ring-spun yarn through woven greige and export-ready finished fabric.',
            'image' => $this->img('assets/images/gallery/gallery-2.jpg'),
            'meta_title' => 'Products | Islam Textile — Yarn, Woven & Finished Fabric',
            'meta_description' => 'Islam Textile product range: cotton yarn and spinning, woven greige and finished fabric, dyeing and finishing services for export buyers in Bangladesh.',
        ]);

        $this->page('gallery', [
            'title' => 'Gallery',
            'lead' => 'Dyeing, weaving, finishing, yarn, and the effluent plant at Islam Textile, Narayanganj.',
        ]);

        $this->page('news', [
            'title' => 'News',
            'lead' => 'Mill notes from Dhaka and Narayanganj.',
        ]);

        $this->page('careers', [
            'title' => 'Careers',
            'lead' => 'Positions based in Dhaka and Narayanganj, Bangladesh.',
        ]);
    }

    private function catalog(): void
    {
        $categories = [
            ['yarn', 'Yarn', 'High-quality cotton yarns for superior strength and consistent performance.', '/products#yarn', 'assets/images/gallery/gallery-7.jpg'],
            ['woven', 'Woven Fabric', 'Durable, versatile fabrics crafted with precision and care.', '/products/woven-fabric', 'assets/images/products/product-0.jpg'],
            ['finished', 'Finished Fabric', 'Ready-to-use fabrics with quality finishing for your exact needs.', '/products#finishing', 'assets/images/products/product-3.jpg'],
        ];
        $categoryIds = [];
        foreach ($categories as $index => [$slug, $name, $text, $href, $image]) {
            $row = ProductCategory::query()->firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'text' => $text,
                'href' => $href,
                'image' => $this->img($image),
                'sort' => $index + 1,
            ]);
            $categoryIds[$name] = $row->id;
        }

        $products = [
            ['combed-cotton-poplin', 'Combed Cotton Poplin', 'Woven Fabric', 'Soft combed cotton poplin for shirts and light apparel programs.', 'Greige poplin woven on the Narayanganj looms. Construction and shade are confirmed in a formal quotation.', '40s × 40s', 'Poplin', 'Greige', '40×40 / 133×72', '130–145', 'assets/images/products/product-0.jpg', '2026-08-01'],
            ['fine-count-poplin', 'Fine Count Poplin', 'Woven Fabric', 'Fine-count poplin for lighter shirting programs.', 'Woven cotton poplin in a finer count. Confirm the construction against the buyer brief.', '60s × 60s', 'Poplin', 'RFD', '60×60 / 173×124', '110–125', 'assets/images/products/product-1.jpg', '2026-07-15'],
            ['cotton-twill-3-1', 'Cotton Twill 3/1', 'Woven Fabric', 'Coarser cotton twill for workwear and heavier programs.', '3/1 twill woven for durability. Finish and shade are confirmed on the order.', '20s × 16s', 'Twill', 'Dyed', '20×16 / 120×60', '220–280', 'assets/images/products/product-0.jpg', '2026-06-20'],
            ['cotton-drill', 'Cotton Drill', 'Finished Fabric', 'Finished cotton drill for repeat bulk programs.', 'Finished drill held for hand and shade on standing programs.', '16s × 12s', 'Twill', 'Finished', '16×12 / 108×56', '250–300', 'assets/images/gallery/gallery-8.jpg', '2026-05-10'],
            ['cotton-oxford', 'Cotton Oxford', 'Woven Fabric', 'Oxford weave for shirting and uniform programs.', 'Cotton oxford woven to a buyer construction.', '30s × 30s', 'Oxford', 'Dyed', '30×30 / 84×72', '140–160', 'assets/images/products/product-1.jpg', '2026-04-02'],
            ['plain-weave-sheeting', 'Plain Weave Sheeting', 'Woven Fabric', 'Plain-weave sheeting in greige cotton.', 'Sheeting construction for home textile programs. Finish is confirmed on the order.', '30s × 30s', 'Plain', 'Greige', '30×30 / 68×68', '120–140', 'assets/images/gallery/gallery-2.jpg', '2026-03-12'],
            ['carded-oe-yarn-20s', 'Carded OE Yarn 20s', 'Yarn', 'Carded open-end cotton yarn for weaving programs.', 'Weaving-grade cotton yarn. Count and package are confirmed in the quotation.', '20s', '', '', '', '', 'assets/images/process/step-06.jpg', '2026-09-05'],
        ];

        foreach ($products as $index => $row) {
            [$slug, $title, $category, $summary, $description, $count, $weave, $finish, $construction, $gsm, $image, $updated] = $row;
            $product = Product::query()->firstOrCreate(['slug' => $slug], [
                'product_category_id' => $categoryIds[$category] ?? null,
                'title' => $title,
                'summary' => $summary,
                'description' => $description,
                'count' => $count ?: null,
                'weave' => $weave ?: null,
                'finish' => $finish ?: null,
                'construction' => $construction ?: null,
                'gsm' => $gsm ?: null,
                'filter_count' => Mill::filterCount($count),
                'status' => 'published',
                'image' => $this->img($image),
                'meta_title' => $title.' | Islam Textile',
                'meta_description' => $summary,
                'updated_on' => $updated,
                'sort' => $index + 1,
            ]);
            ProductImage::query()->firstOrCreate(
                ['product_id' => $product->id, 'path' => $this->img($image)],
                ['sort' => 1]
            );
        }

        $poplin = Product::query()->where('slug', 'combed-cotton-poplin')->first();
        if ($poplin) {
            foreach ([
                'assets/images/products/product-1.jpg',
                'assets/images/products/product-2.jpg',
                'assets/images/gallery/gallery-2.jpg',
            ] as $index => $file) {
                ProductImage::query()->firstOrCreate(
                    ['product_id' => $poplin->id, 'path' => $this->img($file)],
                    ['sort' => $index + 2]
                );
            }
            foreach ([
                ['Composition', '100% Combed Cotton'],
                ['Width', '57″ / 58″ greige · up to 120″ finished'],
                ['End use', 'Dress shirts'],
                ['End use', 'Uniforms'],
                ['End use', 'Corporate'],
                ['End use', 'Blouses'],
            ] as $index => [$label, $value]) {
                ProductSpec::query()->firstOrCreate(
                    ['product_id' => $poplin->id, 'label' => $label, 'value' => $value],
                    ['sort' => $index + 1]
                );
            }
        }
    }

    private function gallery(): void
    {
        $items = [
            ['Factory', 'assets/images/gallery/gallery-1.jpg', 'Effluent treatment plant at the Narayanganj campus', 'Effluent treatment plant'],
            ['Yarn', 'assets/images/gallery/gallery-7.jpg', 'Yarn rolls stored on racking', 'Yarn rolls in store'],
            ['Yarn', 'assets/images/gallery/gallery-2.jpg', 'Fabric on the finishing line', 'Fabric on the finishing line'],
            ['Production', 'assets/images/gallery/gallery-4.jpg', 'Color dispensing laboratory', 'Color dispensing laboratory'],
            ['Production', 'assets/images/gallery/gallery-0.jpg', 'Dyeing machines on the wet-processing floor', 'Dyeing machines'],
            ['Production', 'assets/images/gallery/gallery-3.jpg', 'Sample washing machines used for fabric testing', 'Sample washing machines'],
            ['Yarn', 'assets/images/gallery/gallery-6.jpg', 'White fabric running on the finishing line', 'White fabric on the line'],
            ['Factory', 'assets/images/gallery/gallery-10.jpg', 'Finishing floor aisle at the Narayanganj mill', 'Finishing floor'],
            ['Yarn', 'assets/images/gallery/gallery-13.jpg', 'Printed fabric on the finishing line', 'Printed fabric on the finishing line'],
            ['Production', 'assets/images/gallery/gallery-5.jpg', 'Dyeing machines on the wet-processing floor', 'Dyeing machines'],
            ['Production', 'assets/images/products/product-2.jpg', 'Production line on the mill floor', 'Production line'],
            ['Weaving', 'assets/images/products/product-0.jpg', 'Weaving loom running a cotton warp', 'Weaving loom'],
        ];

        foreach ($items as $index => [$album, $image, $alt, $caption]) {
            $path = $this->img($image);
            GalleryItem::query()->firstOrCreate(
                ['album' => $album, 'image' => $path],
                [
                    'media_id' => Media::query()->where('path', $path)->value('id'),
                    'alt' => $alt,
                    'caption' => $caption,
                    'sort' => $index + 1,
                ]
            );
        }
    }

    private function news(): void
    {
        $posts = [
            ['mill-efficiency-upgrade', 'Mill Efficiency Upgrade Completes Phase One', '2026-08-12', 'operations', 'Loom monitoring and inspection checkpoints for more consistent woven cotton lots.', 'Loom monitoring, in-process inspection, and clearer lot identification across the Narayanganj weaving and finishing floors.', 'assets/images/products/product-0.jpg'],
            ['responsible-cotton-sourcing', 'Responsible Cotton Sourcing Update', '2026-06-04', 'sustainability', 'Traceable cotton inputs and dyehouse water stewardship for export programs.', 'Traceable cotton inputs and dyehouse water stewardship for export programs.', 'assets/images/news/news-0.jpg'],
            ['export-milestone', 'Export Milestone Across 18 Markets', '2026-03-18', 'export', 'Woven cotton shipments expand with consistent greige and finished programs.', 'Woven cotton shipments expand with consistent greige and finished programs.', 'assets/images/news/news-2.jpg'],
            ['lab-shade-matching', 'In-House Lab Shade Matching Upgrade', '2025-11-22', 'quality', 'Expanded physical and chemical testing for yarn, greige, and finished fabric.', 'Expanded physical and chemical testing for yarn, greige, and finished fabric.', 'assets/images/gallery/gallery-3.jpg'],
            ['air-jet-capacity', 'Additional Air-Jet Capacity Online', '2025-09-09', 'operations', 'New weaving capacity supports wider greige widths for apparel buyers.', 'New weaving capacity supports wider greige widths for apparel buyers.', 'assets/images/products/product-1.jpg'],
            ['buyer-visits', 'Spring Buyer Visits at Narayanganj Mill', '2025-05-15', 'export', 'Facility tours focused on spinning-to-finishing traceability for woven cotton.', 'Facility tours focused on spinning-to-finishing traceability for woven cotton.', 'assets/images/news/news-1.jpg'],
            ['etp-capacity', 'ETP Capacity Reinforcement Complete', '2024-12-02', 'sustainability', 'Effluent treatment upgrades sized for continuous dyehouse operation.', 'Effluent treatment upgrades sized for continuous dyehouse operation.', 'assets/images/gallery/gallery-1.jpg'],
            ['poplin-spec', 'Shirting Poplin Spec Tightening', '2024-08-21', 'quality', 'Closer reed/pick tolerances for corporate shirting and uniform programs.', 'Closer reed/pick tolerances for corporate shirting and uniform programs.', 'assets/images/gallery/gallery-2.jpg'],
            ['combed-yarn-range', 'Combed Yarn Count Range Expanded', '2024-02-10', 'operations', 'Ring-spun combed counts Ne 20–40 for weaving and downstream finishing.', 'Ring-spun combed counts Ne 20–40 for weaving and downstream finishing.', 'assets/images/products/product-0.jpg'],
        ];

        foreach ($posts as [$slug, $title, $date, $category, $lead, $body, $cover]) {
            Post::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'lead' => $lead,
                'body' => $body,
                'cover' => $this->img($cover),
                'category' => $category,
                'published_on' => $date,
                'status' => 'published',
                'meta_title' => $title.' | Islam Textile',
                'meta_description' => $lead,
            ]);
        }
    }

    private function careers(): void
    {
        $jobs = [
            ['quality-inspector-woven-fabric', 'Quality Inspector — Woven Fabric', 'Narayanganj, Bangladesh', 'Quality', 'Full-time', 'Inspect greige and finished lots on the weaving and finishing floors.', 'Full-time role in the Quality Department. The work is inspection of greige and finished woven lots, with records that stay with the batch.', 'assets/images/news/news-2.jpg'],
            ['loom-technician', 'Loom Technician', 'Narayanganj, Bangladesh', 'Weaving', 'Full-time', 'Air-jet and rapier weaving.', 'Full-time weaving role for air-jet and rapier looms at the Narayanganj mill.', 'assets/images/products/product-0.jpg'],
            ['merchandising-coordinator', 'Merchandising Coordinator', 'Dhaka, Bangladesh', 'Commercial', 'Full-time', 'Sampling and buyer follow-up.', 'Commercial desk in Dhaka for sampling and buyer follow-up on woven programs.', 'assets/images/admin/career-samples.jpg'],
            ['dyehouse-supervisor', 'Dyehouse Supervisor', 'Narayanganj, Bangladesh', 'Finishing', 'Full-time', 'Reactive and pigment programs.', 'Supervises reactive and pigment dyeing on the Narayanganj wet-processing floor.', 'assets/images/gallery/gallery-5.jpg'],
            ['spinning-shift-lead', 'Spinning Shift Lead', 'Narayanganj, Bangladesh', 'Spinning', 'Full-time', 'Ring-spun combed and carded yarn.', 'Leads a spinning shift for weaving-grade cotton yarn.', 'assets/images/gallery/gallery-7.jpg'],
        ];

        foreach ($jobs as $index => [$slug, $title, $location, $department, $type, $summary, $description, $image]) {
            CareerJob::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'location' => $location,
                'department' => $department,
                'employment_type' => $type,
                'summary' => $summary,
                'description' => $description,
                'image' => $this->img($image),
                'status' => 'open',
                'sort' => $index + 1,
            ]);
        }
    }

    private function page(string $slug, array $attributes, array $fields = []): Page
    {
        $page = Page::query()->firstOrCreate(
            ['slug' => $slug],
            array_merge(['status' => 'published', 'title' => Str::headline($slug)], $attributes)
        );

        foreach ($fields as $key => $value) {
            PageField::query()->firstOrCreate(
                ['page_id' => $page->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return $page->load('fields', 'blocks');
    }

    private function blocks(Page $page, string $group, array $rows): void
    {
        foreach ($rows as $index => $row) {
            PageBlock::query()->firstOrCreate(
                ['page_id' => $page->id, 'group' => $group, 'sort' => $index + 1],
                $row
            );
        }
    }

    private function img(string $source): ?string
    {
        return Mill::copyPublicImage($source);
    }
}
