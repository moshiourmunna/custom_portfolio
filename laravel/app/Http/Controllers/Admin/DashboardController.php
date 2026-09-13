<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerJob;
use App\Models\GalleryItem;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'counts' => [
                'products' => Product::query()->count(),
                'inquiries' => Inquiry::query()->count(),
                'news' => Post::query()->where('status', 'published')->count(),
                'gallery' => GalleryItem::query()->count(),
            ],
            'inquiries' => Inquiry::query()->latest('received_on')->limit(6)->get(),
            'posts' => Post::query()->latest('published_on')->limit(4)->get(['id', 'title', 'slug', 'published_on', 'status']),
            'jobs' => CareerJob::query()->where('status', 'open')->orderBy('sort')->limit(5)->get(['id', 'title', 'location', 'status']),
        ]);
    }
}
