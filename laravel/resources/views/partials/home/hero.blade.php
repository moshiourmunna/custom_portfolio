@if($page->sectionActive('hero') && mill_filled($page->field('hero_headline')))
  <section class="hero">
    <div class="hero__media">
      @if(mill_url($page->field('hero_image')))
        <img src="{{ mill_url($page->field('hero_image')) }}" alt="Weaving loom at the Islam Textile mill in Narayanganj">
      @endif
    </div>
    <div class="hero__overlay" aria-hidden="true"></div>
    <div class="container hero__content">
      <div class="hero__lockup">
        <img class="hero__brand-mark" src="{{ $site->logoUrl() }}" alt="">
        <div>
          <p class="hero__brand-name">{{ $site->site_name }}</p>
          @if(mill_filled($site->tagline))<p class="hero__brand-tag">{{ $site->tagline }}</p>@endif
        </div>
      </div>
      <h1>{{ $page->field('hero_headline') }}</h1>
      @if(mill_filled($page->field('hero_lead')))<p class="hero__lead">{{ $page->field('hero_lead') }}</p>@endif
      <div class="btn-group">
        @if(mill_filled($page->field('hero_primary_label')))
          <a href="{{ $page->field('hero_primary_href') ?: route('contact') }}" class="btn btn--accent">{{ $page->field('hero_primary_label') }}</a>
        @endif
        @if(mill_filled($page->field('hero_secondary_label')))
          <a href="{{ $page->field('hero_secondary_href') ?: route('products') }}" class="btn btn--outline-light">{{ $page->field('hero_secondary_label') }} →</a>
        @endif
      </div>
    </div>
  </section>
@endif
