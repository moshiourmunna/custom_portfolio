@extends('layouts.site', ['metaTitle' => 'Gallery | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  <section class="mill-hero">
    <img class="mill-hero__bg" src="{{ mill_url($page->image) ?: mill_url('media/gallery/gallery-2.jpg') }}" alt="">
    <div class="container mill-hero__content">
      <h1>{{ $page->title ?: 'Gallery' }}</h1>
      <nav class="mill-hero__crumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">›</span> <span>Gallery</span>
      </nav>
    </div>
  </section>

  <section class="section mill-gallery" data-gallery>
    <div class="container mill-layout">
      <aside class="mill-side" aria-label="Albums">
        <h2>Filter albums</h2>
        <div class="mill-filters" role="group" aria-label="Filter gallery albums">
          <button type="button" class="is-active" data-filter="all">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="6.5" height="6.5" rx="1"/><rect x="13.5" y="4" width="6.5" height="6.5" rx="1"/><rect x="4" y="13.5" width="6.5" height="6.5" rx="1"/><rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1"/></svg>
            All Photos
          </button>
          <button type="button" data-filter="factory">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V9l5 3V9l5 3V6h6v14H4z"/><path d="M8 20v-3M12 20v-3M16 20v-3"/></svg>
            Factory
          </button>
          <button type="button" data-filter="production">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="2.4"/><path d="M12 4.2v2.1M12 17.7v2.1M4.2 12h2.1M17.7 12h2.1M6.4 6.4l1.5 1.5M16.1 16.1l1.5 1.5M17.6 6.4l-1.5 1.5M7.9 16.1l-1.5 1.5"/></svg>
            Production
          </button>
          <button type="button" data-filter="yarn">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7.5h14M5 12h14M5 16.5h14"/></svg>
            Yarn &amp; Fabric
          </button>
        </div>
        <div class="mill-note">
          <div class="mill-note__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M8 7.5h8l1.2 1.6H20v9H4v-9h2.8L8 7.5z"/><circle cx="12" cy="13" r="2.4"/></svg>
          </div>
          <h3>Capturing the mill</h3>
          <p>{{ $page->lead ?: 'Dyeing, weaving, finishing, yarn, and the effluent plant at Islam Textile, Narayanganj.' }}</p>
        </div>
      </aside>

      <div class="mill-board">
        <div class="mill-toolbar">
          <p data-filter-status>Showing all photos ({{ $items->count() }})</p>
          <label>
            Sort by:
            <select data-gallery-sort aria-label="Sort gallery">
              <option value="newest">Latest</option>
              <option value="oldest">Oldest</option>
            </select>
          </label>
        </div>
        <div class="mill-grid" data-gallery-grid>
          @foreach($items as $item)
            @php $date = now()->startOfYear()->addDays(max(0, 200 - (int) $item->sort))->format('Y-m-d'); @endphp
            <button type="button" class="mill-item" data-load-item data-category="{{ strtolower($item->album) }}" data-date="{{ $date }}" data-lightbox="{{ mill_url($item->image) }}" aria-label="{{ $item->caption ?: $item->alt }}">
              <img src="{{ mill_url($item->image) }}" alt="{{ $item->alt }}">
            </button>
          @endforeach
        </div>
        <p class="mill-more">
          <button type="button" class="btn btn--outline" data-load-more data-page-size="9">Load more</button>
        </p>
      </div>
    </div>
  </section>

  <div class="lightbox" role="dialog" aria-modal="true" aria-label="Image lightbox">
    <button type="button" class="lightbox__close" aria-label="Close">&times;</button>
    <button type="button" class="lightbox__nav lightbox__nav--prev" aria-label="Previous">‹</button>
    <img src="" alt="">
    <button type="button" class="lightbox__nav lightbox__nav--next" aria-label="Next">›</button>
  </div>
@endsection
