<?php

namespace App\Http\Controllers;

use App\Models\CareerJob;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $base = rtrim(Setting::current()->canonical_base ?: config('app.url'), '/');
        $urls = ['/', '/about', '/products', '/products/yarn', '/products/woven-fabric', '/products/finished-fabric', '/process', '/facilities', '/quality', '/sustainability', '/gallery', '/news', '/careers', '/contact', '/privacy', '/terms'];

        foreach (Product::query()->where('status', 'published')->pluck('slug') as $slug) {
            $urls[] = '/products/'.$slug;
        }
        foreach (Post::query()->where('status', 'published')->pluck('slug') as $slug) {
            $urls[] = '/news/'.$slug;
        }
        foreach (CareerJob::query()->where('status', 'open')->pluck('slug') as $slug) {
            $urls[] = '/careers/'.$slug;
        }

        $body = collect($urls)->map(fn (string $path) => '<url><loc>'.e($base.$path).'</loc></url>')->implode('');

        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$body.'</urlset>', 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robots(): Response
    {
        $base = rtrim(Setting::current()->canonical_base ?: config('app.url'), '/');
        $body = "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: {$base}/sitemap.xml\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
