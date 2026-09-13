@extends('layouts.site', ['metaTitle' => 'News | '.$site->site_name, 'metaDescription' => $page?->lead])
@section('content')
  @php $years = $posts->map(fn ($post) => $post->published_on?->format('Y'))->filter()->unique()->sortDesc(); @endphp
  <section class="news-hero">
    <img class="news-hero__bg" src="{{ mill_url($page?->image) ?: mill_url('media/gallery/gallery-2.jpg') }}" alt="">
    <div class="container news-hero__content">
      <p class="news-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> News</p>
      <h1>News &amp; Updates</h1>
      <p>Operational updates, quality milestones, and export news from the Narayanganj mill.</p>
    </div>
  </section>

  <section class="section news-board" data-news data-page-size="6">
    <div class="container news-board__grid">
      <aside class="news-side">
        <div class="news-panel">
          <h2>Categories</h2>
          <div class="news-cats" role="group" aria-label="News categories">
            <button type="button" class="is-active" data-news-cat="all">All news</button>
            @foreach(['operations' => 'Operations', 'quality' => 'Quality', 'sustainability' => 'Sustainability', 'export' => 'Export'] as $value => $label)
              <button type="button" data-news-cat="{{ $value }}">{{ $label }}</button>
            @endforeach
          </div>
        </div>
        <div class="news-panel">
          <label for="news-year">Filter by year</label>
          <select id="news-year" data-news-year>
            <option value="all">All years</option>
            @foreach($years as $year)
              <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
          </select>
        </div>
        <div class="news-panel">
          <label for="news-search">Search</label>
          <input id="news-search" type="search" data-news-query placeholder="Search updates" autocomplete="off">
        </div>
      </aside>

      <div class="news-main">
        <div class="news-toolbar">
          <p data-news-status>Showing {{ $posts->count() }} results</p>
          <label class="news-sort">
            <span class="sr-only">Sort</span>
            <select data-news-sort aria-label="Sort news">
              <option value="newest">Newest first</option>
              <option value="oldest">Oldest first</option>
            </select>
          </label>
        </div>
        <div class="news-grid">
          @foreach($posts as $post)
            <article class="news-card" data-news-card data-category="{{ $post->category }}" data-year="{{ $post->published_on?->format('Y') }}" data-date="{{ $post->published_on?->format('Y-m-d') }}" data-search="{{ strtolower($post->title.' '.$post->lead) }}">
              @if(mill_url($post->cover))
                <a href="{{ route('news.show', $post->slug) }}" class="news-card__media">
                  <img src="{{ mill_url($post->cover) }}" alt="">
                </a>
              @endif
              <div class="news-card__body">
                <p class="news-card__meta">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="1.5"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                  {{ $post->published_on?->format('j M Y') }} @if($post->category) · {{ ucfirst($post->category) }} @endif
                </p>
                <h3><a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a></h3>
                @if(mill_filled($post->lead))<p>{{ $post->lead }}</p>@endif
                <a href="{{ route('news.show', $post->slug) }}" class="news-card__more">Read more →</a>
              </div>
            </article>
          @endforeach
        </div>
        <div class="pagination" data-pagination aria-label="News pagination"></div>
      </div>
    </div>
  </section>
@endsection
