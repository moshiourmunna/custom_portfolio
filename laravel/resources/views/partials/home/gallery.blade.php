@if($page->sectionActive('gallery') && mill_filled($page->field('gallery_title')) && $strip->isNotEmpty())
  <section class="section section--surface home-gallery-section">
    <div class="container">
      <header class="section__head section__head--center reveal">
        <p class="section__eyebrow">Gallery</p>
        <h2 class="section__title">{{ $page->field('gallery_title') }}</h2>
        @if(mill_filled($page->field('gallery_lead')))<p class="section__lead">{{ $page->field('gallery_lead') }}</p>@endif
      </header>
      <div class="home-gallery reveal">
        @foreach($strip as $item)
          <a class="home-gallery__item" href="{{ $item->href ?: route('gallery') }}">
            @if(mill_url($item->image))<img src="{{ mill_url($item->image) }}" alt="{{ $item->text }}">@endif
            @if(mill_filled($item->title))<span class="home-gallery__label">{{ $item->title }}</span>@endif
          </a>
        @endforeach
      </div>
      <p class="section-action"><a href="{{ route('gallery') }}" class="link-more link-more--serif">Open Gallery →</a></p>
    </div>
  </section>
@endif
