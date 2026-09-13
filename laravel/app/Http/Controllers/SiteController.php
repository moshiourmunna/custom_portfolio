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
            'footer' => 'slim',
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

    public function woven(): View
    {
        $products = Product::query()
            ->with('category')
            ->where('status', 'published')
            ->whereHas('category', fn ($query) => $query->whereIn('slug', ['woven', 'finished']))
            ->orderBy('sort')
            ->get();

        return view('pages.woven', [
            'page' => $this->page('products'),
            'products' => $products,
        ]);
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
