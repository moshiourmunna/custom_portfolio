@extends('layouts.site', [
  'metaTitle' => $page->meta_title,
  'metaDescription' => $page->meta_description,
  'ogImage' => $page->field('hero_image'),
])

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => $site->site_name,
  'url' => rtrim($site->canonical_base ?: url('/'), '/').'/',
  'email' => $site->email,
  'telephone' => $site->phone,
  'description' => $site->meta_description,
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
  @php
    $stats = $page->blocksFor('stats');
    $products = $page->blocksFor('products');
    $insights = $page->blocksFor('insights');
    $why = $page->blocksFor('why_items');
    $steps = $page->blocksFor('steps');
    $bullets = $page->blocksFor('facility_bullets');
    $points = $page->blocksFor('quality_points');
    $markets = $page->blocksFor('markets');
    $strip = $page->blocksFor('gallery_strip');
    $figures = $page->blocksFor('facility_figures');
    $statIcons = ['stat-looms', 'stat-yarn', 'stat-globe', 'stat-people'];
    $productIcons = ['product-yarn', 'product-weave', 'product-finish'];
    $productHrefs = [
      'Yarn' => route('products.yarn'),
      'Woven Fabric' => route('products.woven'),
      'Finished Fabric' => route('products.finished'),
    ];
    $whyIcons = ['trust-shield', 'trust-mill', 'trust-clock', 'trust-export'];
    $stepIcons = ['step-fiber', 'step-spin', 'step-weave', 'step-dye', 'step-finish', 'step-dispatch'];
    $certIcons = ['cert-lab', 'cert-process', 'cert-doc', 'cert-shield'];
  @endphp

  @if(mill_filled($page->field('hero_headline')))
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

  @if($stats->isNotEmpty())
    <section class="stats" aria-label="Company highlights">
      <div class="container stats__grid">
        @foreach($stats as $stat)
          @if(mill_filled($stat->value))
            <div class="stat reveal">
              <div class="stat__icon" aria-hidden="true">@include('partials.icon', ['icon' => $statIcons[$loop->index] ?? 'stat-looms'])</div>
              <div class="stat__copy">
                <div class="stat__value" data-count="{{ $stat->value }}" data-suffix="{{ $stat->suffix }}">0</div>
                <div class="stat__label">{{ $stat->title }}</div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </section>
  @endif

  @if($products->isNotEmpty())
    <section class="home-products">
      <div class="container">
        <div class="home-products__grid">
          @foreach($products as $item)
            <article class="home-products__item reveal" @if($loop->first) id="yarn" @endif @if($loop->last) id="finishing" @endif>
              <div class="home-products__icon" aria-hidden="true">@include('partials.icon', ['icon' => $productIcons[$loop->index] ?? 'product-weave'])</div>
              <div class="home-products__body">
                @if(mill_filled($item->title))<h3>{{ $item->title }}</h3>@endif
                @if(mill_filled($item->text))<p>{{ $item->text }}</p>@endif
                @if(mill_filled($productHrefs[$item->title] ?? $item->href))<a href="{{ $productHrefs[$item->title] ?? $item->href }}" class="link-more link-more--serif">View Products →</a>@endif
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="home-insight">
    <div class="container">
      <div class="home-insight__grid">
        @foreach($insights as $item)
          <article class="home-insight__col reveal @if($item->title === 'Latest News') home-insight__col--news @endif">
            <div class="home-insight__content">
              @if(mill_filled($item->title))<h3>{{ $item->title }}</h3>@endif
              @if($item->title === 'Latest News')
                <div class="news-teaser">
                  @foreach($posts as $post)
                    <div class="news-teaser__item">
                      <div class="news-teaser__date">{{ $post->published_on?->format('M j, Y') }}</div>
                      <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
                    </div>
                  @endforeach
                </div>
              @elseif(mill_filled($item->text))
                <p>{{ $item->text }}</p>
              @endif
              @if(mill_filled($item->link_label))
                <a href="{{ $item->href }}" class="link-more link-more--serif">{{ $item->link_label }} →</a>
              @endif
            </div>
            <div class="home-insight__visual @if($item->title === 'Sustainability') home-insight__visual--leaf @endif" aria-hidden="true">
              @if($item->title === 'Sustainability')
                @include('partials.icon', ['icon' => 'leaf'])
              @elseif(mill_url($item->image))
                <img src="{{ mill_url($item->image) }}" alt="">
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  @if(mill_filled($page->field('why_title')))
    <section class="section home-why">
      <div class="container">
        <header class="section__head section__head--center reveal">
          <p class="section__eyebrow">Why Islam Textile</p>
          <h2 class="section__title">{{ $page->field('why_title') }}</h2>
          @if(mill_filled($page->field('why_lead')))<p class="section__lead">{{ $page->field('why_lead') }}</p>@endif
        </header>
        <div class="trust-reasons">
          @foreach($why as $item)
            <article class="trust-reason reveal">
              <div class="trust-reason__icon" aria-hidden="true">@include('partials.icon', ['icon' => $whyIcons[$loop->index] ?? 'trust-shield'])</div>
              @if(mill_filled($item->title))<h3>{{ $item->title }}</h3>@endif
              @if(mill_filled($item->text))<p>{{ $item->text }}</p>@endif
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('integration_title')))
    <section class="section section--surface home-integration">
      <div class="container">
        <header class="section__head section__head--center reveal">
          <p class="section__eyebrow">Vertical Integration</p>
          <h2 class="section__title">{{ $page->field('integration_title') }}</h2>
          @if(mill_filled($page->field('integration_lead')))<p class="section__lead">{{ $page->field('integration_lead') }}</p>@endif
        </header>
        <ol class="home-steps">
          @foreach($steps as $step)
            <li class="home-step reveal">
              <div class="home-step__marker" aria-hidden="true"><span class="home-step__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div>
              <div class="home-step__body">
                <div class="home-step__icon" aria-hidden="true">@include('partials.icon', ['icon' => $stepIcons[$loop->index] ?? 'step-weave'])</div>
                @if(mill_filled($step->title))<h3>{{ $step->title }}</h3>@endif
                @if(mill_filled($step->text))<p>{{ $step->text }}</p>@endif
                @if(mill_filled($step->meta))<p class="home-step__spec">{{ $step->meta }}</p>@endif
              </div>
            </li>
          @endforeach
        </ol>
        <div class="integration-gains reveal" aria-label="Why buyers choose an integrated mill">
          <article>
            <p class="integration-gains__kicker">Accountability</p>
            <h3>One mill partner</h3>
            <p>Spinning, weaving, and finishing on one Narayanganj campus — a single team for the program.</p>
          </article>
          <article>
            <p class="integration-gains__kicker">Traceability</p>
            <h3>Lot identity held</h3>
            <p>Batch records travel from fiber blend through shade approval to the packed roll.</p>
          </article>
          <article>
            <p class="integration-gains__kicker">Programs</p>
            <h3>Built for repeats</h3>
            <p>Construction, hand, and shade held for seasonal peaks and standing export orders.</p>
          </article>
        </div>
        <div class="section-action">
          <div class="btn-group">
            <a href="{{ route('process') }}" class="btn btn--primary">See Full Process</a>
            <a href="{{ route('contact') }}" class="btn btn--outline">Request Quote</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('facilities_title')))
    <section class="section home-facilities">
      <div class="container home-split">
        <div class="home-split__media reveal">
          @if(mill_url($page->field('facilities_image')))
            <img src="{{ mill_url($page->field('facilities_image')) }}" alt="Air-jet weaving loom at the Islam Textile mill in Narayanganj">
          @endif
          <p class="home-split__caption">Narayanganj campus · Weaving floor</p>
        </div>
        <div class="home-split__body reveal">
          <p class="section__eyebrow">Facilities &amp; Capacity</p>
          <h2 class="section__title section__title--display">{{ $page->field('facilities_title') }}</h2>
          @if(mill_filled($page->field('facilities_lead')))<p class="section__lead">{{ $page->field('facilities_lead') }}</p>@endif
          <div class="home-split__metrics" aria-label="Mill capacity">
            @foreach($figures as $figure)
              <div><strong data-count="{{ $figure->value }}" data-suffix="{{ $figure->suffix }}">0</strong><span>{{ $figure->title }}</span></div>
            @endforeach
          </div>
          <ul class="campus-points">
            @foreach($bullets as $bullet)
              <li><strong>{{ $bullet->title }}</strong><span>{{ $bullet->text }}</span></li>
            @endforeach
          </ul>
          <p class="campus-line">Spinning · Weaving · Processing · Laboratory · Warehouse · ETP</p>
          <div class="btn-group">
            <a href="{{ route('facilities') }}" class="btn btn--primary">Explore Facilities</a>
            <a href="{{ route('contact') }}" class="btn btn--outline">Schedule a Visit</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('quality_title')))
    <section class="section section--surface home-quality">
      <div class="container">
        <header class="section__head section__head--center reveal">
          <p class="section__eyebrow">Quality &amp; Certifications</p>
          <h2 class="section__title">{{ $page->field('quality_title') }}</h2>
          @if(mill_filled($page->field('quality_lead')))<p class="section__lead">{{ $page->field('quality_lead') }}</p>@endif
        </header>
        <div class="cert-strip reveal">
          @foreach($points as $point)
            <article class="cert-strip__item">
              <div class="cert-strip__icon" aria-hidden="true">@include('partials.icon', ['icon' => $certIcons[$loop->index] ?? 'cert-shield'])</div>
              @if(mill_filled($point->title))<h3>{{ $point->title }}</h3>@endif
              @if(mill_filled($point->text))<p>{{ $point->text }}</p>@endif
            </article>
          @endforeach
        </div>
        <p class="quality-gates">Yarn check · Loom-side · 4-point · Laboratory · Shade · Dispatch</p>
        <div class="section-action">
          <div class="btn-group">
            <a href="{{ route('quality') }}" class="btn btn--primary">View Quality</a>
            <a href="{{ route('contact') }}" class="btn btn--outline">Request QA Documents</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('markets_title')))
    <section class="section home-markets">
      <div class="container home-split home-split--reverse">
        <div class="home-split__media reveal">
          <img src="{{ mill_url('media/gallery/gallery-5.jpg') }}" alt="Dyeing machines on the wet-processing floor at Islam Textile">
          <p class="home-split__caption">Dyeing floor · Wet processing</p>
        </div>
        <div class="home-split__body reveal">
          <p class="section__eyebrow">Markets We Serve</p>
          <h2 class="section__title section__title--display">{{ $page->field('markets_title') }}</h2>
          @if(mill_filled($page->field('markets_lead')))<p class="section__lead">{{ $page->field('markets_lead') }}</p>@endif
          <div class="markets-grid">
            @foreach($markets as $market)
              <article class="market-chip">
                @if(mill_filled($market->title))<h3>{{ $market->title }}</h3>@endif
                @if(mill_filled($market->text))<p>{{ $market->text }}</p>@endif
              </article>
            @endforeach
          </div>
          <p class="markets-note">Program planning in Dhaka. Mill execution in Narayanganj. Export via Chattogram.</p>
          <div class="btn-group">
            <a href="{{ route('contact') }}" class="btn btn--primary">Request Quote</a>
            <a href="{{ route('products.woven') }}" class="btn btn--outline">Browse Fabrics</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('gallery_title')) && $strip->isNotEmpty())
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

  @if(mill_filled($page->field('careers_title')))
    <section class="section home-careers">
      <div class="container">
        <div class="careers-band reveal">
          <div>
            <p class="section__eyebrow">Careers</p>
            <h2>{{ $page->field('careers_title') }}</h2>
            @if(mill_filled($page->field('careers_lead')))<p>{{ $page->field('careers_lead') }}</p>@endif
          </div>
          @if(mill_filled($page->field('careers_button')))
            <a href="{{ route('careers') }}" class="btn btn--primary">{{ $page->field('careers_button') }}</a>
          @endif
        </div>
      </div>
    </section>
  @endif

  @if(mill_filled($page->field('cta_title')))
    <section class="cta-band cta-band--home">
      <div class="container cta-band__inner">
        <div>
          <h2>{{ $page->field('cta_title') }}</h2>
          @if(mill_filled($page->field('cta_lead')))<p>{{ $page->field('cta_lead') }}</p>@endif
        </div>
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Request Quote →</a>
        <div class="cta-band__contact">
          @if($site->telHref())<a href="{{ $site->telHref() }}">@include('partials.icon', ['icon' => 'phone']) {{ $site->phone }}</a>@endif
          @if(mill_filled($site->email))<a href="mailto:{{ $site->email }}">@include('partials.icon', ['icon' => 'envelope']) {{ $site->email }}</a>@endif
        </div>
      </div>
    </section>
  @endif
@endsection
