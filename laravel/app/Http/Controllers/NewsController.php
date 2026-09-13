<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('pages.news', [
            'page' => Page::query()->where('slug', 'news')->first(),
            'posts' => Post::query()->where('status', 'published')->orderByDesc('published_on')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('status', 'published')->firstOrFail();
        $related = Post::query()
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_on')
            ->limit(3)
            ->get();

        return view('pages.article', compact('post', 'related'));
    }
}
