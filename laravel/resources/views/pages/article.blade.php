@extends('layouts.site', [
  'metaTitle' => $post->meta_title ?: $post->title.' | '.$site->site_name,
  'metaDescription' => $post->meta_description ?: $post->lead,
  'ogImage' => $post->cover,
  'ogType' => 'article',
])
@section('content')
  <section class="story-hero">
    @if(mill_url($post->cover))<img class="story-hero__bg" src="{{ mill_url($post->cover) }}" alt="">@endif
    <div class="container story-hero__content">
      <a class="story-hero__back" href="{{ route('news') }}">← Back to News</a>
      <p class="story-hero__kicker">News</p>
      <h1>{{ $post->title }}</h1>
      @if(mill_filled($post->lead))<p>{{ $post->lead }}</p>@endif
    </div>
  </section>

  <section class="section story">
    <div class="container story__grid">
      <aside class="story-share" aria-label="Share this article">
        <span>Share this article</span>
        <button type="button" data-share="facebook" aria-label="Share on Facebook">f</button>
        <button type="button" data-share="linkedin" aria-label="Share on LinkedIn">in</button>
        <button type="button" data-share="email" aria-label="Share by email">
          <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M4 7l8 6 8-6"/></svg>
        </button>
      </aside>

      <article class="story-body">
        @if($post->published_on)<p class="story-body__date">{{ $post->published_on->format('j F Y') }}</p>@endif
        <h2>{{ $post->title }}</h2>
        @if(mill_filled($post->body))
          @foreach(preg_split("/\n\s*\n/", trim($post->body)) as $paragraph)
            @if(mill_filled($paragraph))<p>{{ $paragraph }}</p>@endif
          @endforeach
        @endif
        <p><a href="{{ route('contact') }}" class="btn btn--primary">Discuss a fabric program</a></p>
      </article>

      <aside class="story-aside">
        <div class="story-related">
          <h2>Related news</h2>
          @foreach($related as $item)
            <a class="story-related__item" href="{{ route('news.show', $item->slug) }}">
              @if(mill_url($item->cover))<img src="{{ mill_url($item->cover) }}" alt="">@endif
              <span>
                @if(mill_filled($item->category))<span class="story-related__cat">{{ ucfirst($item->category) }}</span>@endif
                <strong>{{ $item->title }}</strong>
                <span>{{ $item->published_on?->format('j M Y') }}</span>
              </span>
            </a>
          @endforeach
        </div>
        <div class="story-subscribe">
          <div class="story-subscribe__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M4 7l8 6 8-6"/></svg>
          </div>
          <h2>Stay updated</h2>
          <p>Mill and product notes from the Dhaka desk. Occasional emails.</p>
          @if(session('newsletter'))<p>{{ session('newsletter') }}</p>@endif
          <form data-newsletter action="{{ route('newsletter.store') }}" method="post">
            @csrf
            <input type="text" name="website_url" tabindex="-1" autocomplete="off" hidden>
            <label class="sr-only" for="newsletter-email">Email</label>
            <input id="newsletter-email" type="email" name="email" required placeholder="you@company.com" autocomplete="email">
            <button type="submit">Subscribe now</button>
          </form>
        </div>
      </aside>
    </div>
  </section>

  <section class="story-facts" aria-label="Mill facts">
    <div class="container story-facts__grid">
      <article>
        <div class="story-facts__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg></div>
        <h2>Held on the lot</h2>
        <p>Inspection notes stay with the woven cotton shipment.</p>
      </article>
      <article>
        <div class="story-facts__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 19h16M6 19V9l6-5 6 5v10"/></svg></div>
        <h2>One mill pathway</h2>
        <p>Yarn, weaving, and finishing in Narayanganj.</p>
      </article>
      <article>
        <div class="story-facts__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/></svg></div>
        <h2>Export desk</h2>
        <p>Buyer programs from Dhaka, packed for Chattogram.</p>
      </article>
      <article>
        <div class="story-facts__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20h16M6 20V9l6-5 6 5v11"/><path d="M10 20v-5h4v5"/></svg></div>
        <h2>Mill visits</h2>
        <p>Narayanganj tours by appointment for buyers.</p>
      </article>
    </div>
  </section>
@endsection
