<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $page = $this->page('home');

        return view('pages.home', [
            'page' => $page,
            'posts' => Post::query()->where('status', 'published')->orderByDesc('published_on')->limit(2)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', ['page' => $this->page('about')]);
    }

    public function process(): View
    {
        return view('pages.process', ['page' => $this->page('process')]);
    }

    public function facilities(): View
    {
        return view('pages.facilities', ['page' => $this->page('facilities')]);
    }

    public function quality(): View
    {
        return view('pages.quality', ['page' => $this->page('quality')]);
    }

    public function sustainability(): View
    {
        return view('pages.sustainability', ['page' => $this->page('sustainability')]);
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'page' => $this->page('gallery'),
            'items' => \App\Models\GalleryItem::query()->orderBy('sort')->get(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', ['page' => $this->page('contact')]);
    }

    public function privacy(): View
    {
        return view('pages.legal', ['page' => $this->page('privacy'), 'kind' => 'privacy']);
    }

    public function terms(): View
    {
        return view('pages.legal', ['page' => $this->page('terms'), 'kind' => 'terms']);
    }

    public function quoteSuccess(): View
    {
        return view('pages.success', ['page' => $this->page('success')]);
    }

    public function products(): View
    {
        return view('pages.products', [
            'page' => $this->page('products'),
            'categories' => \App\Models\ProductCategory::query()->orderBy('sort')->get(),
        ]);
    }

    public function yarn(): View
    {
        return $this->catalog('yarn');
    }

    public function woven(): View
    {
        return $this->catalog('woven');
    }

    public function finished(): View
    {
        return $this->catalog('finished');
    }

    private function catalog(string $slug): View
    {
        $products = Product::query()
            ->with('category')
            ->where('status', 'published')
            ->whereHas('category', fn ($query) => $query->where('slug', $slug))
            ->orderBy('sort')
            ->get();

        return view('pages.catalog', [
            'catalog' => $this->catalogCopy($slug),
            'products' => $products,
        ]);
    }

    private function catalogCopy(string $slug): array
    {
        return match ($slug) {
            'yarn' => [
                'title' => 'Yarn',
                'meta' => 'Combed and carded cotton yarn for weaving programs.',
                'hero' => 'media/gallery/gallery-7.jpg',
                'note' => 'Combed and carded ring-spun cotton yarn in counts suited for shirting, sheeting, and industrial woven applications.',
                'mark' => 'yarn',
                'filters' => [
                    ['key' => 'count', 'label' => 'Count', 'options' => ['fine' => 'Fine (Ne 50+)', 'medium' => 'Medium (Ne 30–40)', 'coarse' => 'Coarse (Ne ≤20)']],
                ],
                'action' => 'Request sample',
                'empty' => 'No yarn counts match these filters. Clear them, or request a count below.',
                'cta_title' => 'Need another count?',
                'cta_text' => 'Share the count and end use. The Dhaka team will review a weaving-grade yarn program.',
                'cta_label' => 'Request a count',
            ],
            'finished' => [
                'title' => 'Finished Fabric',
                'meta' => 'Dyed and finished cotton fabric for export-ready programs.',
                'hero' => 'media/gallery/gallery-13.jpg',
                'note' => 'Reactive and pigment dyeing, mercerization, sanforizing, and soft finishes for export-ready cotton fabric.',
                'mark' => 'finish',
                'filters' => [
                    ['key' => 'finish', 'label' => 'Finish', 'options' => ['dyed' => 'Dyed', 'finished' => 'Soft finished']],
                    ['key' => 'weave', 'label' => 'Weave', 'options' => ['poplin' => 'Poplin', 'twill' => 'Twill', 'oxford' => 'Oxford', 'plain' => 'Plain']],
                    ['key' => 'count', 'label' => 'Count', 'options' => ['fine' => 'Fine (Ne 50+)', 'medium' => 'Medium (Ne 30–40)', 'coarse' => 'Coarse (Ne ≤20)']],
                ],
                'action' => 'Request swatch',
                'empty' => 'No finished fabrics match these filters. Clear them, or request a finish below.',
                'cta_title' => 'Need another finish?',
                'cta_text' => 'Share the construction and finish. The mill will review feasibility for the program.',
                'cta_label' => 'Request a finish',
            ],
            default => [
                'title' => 'Woven Fabric',
                'meta' => 'Greige and finished cotton fabrics woven on air-jet and rapier looms.',
                'hero' => 'media/products/product-3.jpg',
                'note' => 'Cotton greige and finished woven fabrics — filter by count, weave, and finish for your next program.',
                'mark' => 'weave',
                'filters' => [
                    ['key' => 'count', 'label' => 'Count (yarn count)', 'options' => ['fine' => 'Fine (Ne 50+)', 'medium' => 'Medium (Ne 30–40)', 'coarse' => 'Coarse (Ne ≤20)']],
                    ['key' => 'weave', 'label' => 'Weave', 'options' => ['poplin' => 'Poplin', 'twill' => 'Twill', 'oxford' => 'Oxford', 'plain' => 'Plain']],
                    ['key' => 'finish', 'label' => 'Finish', 'options' => ['greige' => 'Greige', 'rfd' => 'RFD', 'dyed' => 'Dyed', 'finished' => 'Soft finished']],
                ],
                'action' => 'Request swatch',
                'empty' => 'No constructions match these filters. Clear them, or request a custom program below.',
                'cta_title' => "Can't Find Your Construction?",
                'cta_text' => 'Share your spec sheet — our Dhaka merchandising team will review feasibility.',
                'cta_label' => 'Request Custom Program',
            ],
        };
    }

    public function product(string $slug): View
    {
        $product = Product::query()->with(['category', 'images', 'specs'])->where('slug', $slug)->where('status', 'published')->firstOrFail();
        $related = Product::query()
            ->where('status', 'published')
            ->where('id', '!=', $product->id)
            ->when($product->product_category_id, fn ($query) => $query->where('product_category_id', $product->product_category_id))
            ->orderBy('sort')
            ->limit(3)
            ->get();

        return view('pages.product', compact('product', 'related'));
    }

    private function page(string $slug): Page
    {
        return Page::query()->with(['fields', 'blocks'])->where('slug', $slug)->where('status', 'published')->firstOrFail();
    }
}
