@extends('layouts.site', ['metaTitle' => $page->meta_title ?: $page->title.' | '.$site->site_name, 'metaDescription' => $page->meta_description ?: $page->field('hero_lead')])
@section('content')
  @php $timeIcons = ['time-found', 'time-spin', 'time-loom', 'time-dye', 'time-globe']; $vmvIcons = ['vmv-vision', 'vmv-mission', 'vmv-values']; @endphp
  <section class="about-hero">
    <img class="about-hero__bg" src="{{ mill_url('media/products/product-0.jpg') }}" alt="" aria-hidden="true">
    <div class="about-hero__shade" aria-hidden="true"></div>
    <div class="container about-hero__content">
      <p class="about-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> About Us</p>
      <h1>{{ $page->title }}</h1>
      @if(mill_filled($site->tagline))<p class="about-hero__tag">{{ $site->tagline }}</p>@endif
      @if(mill_filled($page->field('hero_lead')))<p class="about-hero__lead">{{ $page->field('hero_lead') }}</p>@endif
    </div>
  </section>

  <section class="about-facts" aria-label="Company facts">
    <div class="container about-facts__grid">
      <div><strong>1990</strong><span>Founded in Narayanganj</span></div>
      <div><strong>One mill</strong><span>Spinning to finishing</span></div>
      <div><strong>Dhaka</strong><span>Buyer program desk</span></div>
      <div><strong>25+</strong><span>Countries served</span></div>
    </div>
  </section>

  <section class="section about-story-section">
    <div class="container about-story">
      <div class="heritage reveal">
        @if(mill_url($page->image))
          <div class="heritage__img"><img src="{{ mill_url($page->image) }}" alt="Fabric on the finishing line at Islam Textile"></div>
        @endif
        <div class="heritage__copy">
          <p class="section__eyebrow">Our Heritage &amp; Story</p>
          @if(mill_filled($page->field('heritage_title')))<h2 class="section__title section__title--display">{{ $page->field('heritage_title') }}</h2>@endif
          @if(mill_filled($page->field('heritage_text')))<p>{{ $page->field('heritage_text') }}</p>@endif
          @if(mill_filled($page->field('heritage_more')))<p>{{ $page->field('heritage_more') }}</p>@endif
          <div class="btn-group">
            <a href="{{ route('facilities') }}" class="btn btn--primary">Explore Facilities</a>
            <a href="{{ route('process') }}" class="btn btn--outline">See the Process</a>
          </div>
        </div>
      </div>
      <aside class="vmv reveal" aria-label="Vision, mission, and values">
        <p class="vmv__label">Vision, Mission &amp; Values</p>
        @if(mill_filled($page->field('vision')))
          <article class="vmv__item">
            <div class="vmv__icon" aria-hidden="true">@include('partials.icon', ['icon' => 'vmv-vision'])</div>
            <div><h3>Vision</h3><p>{{ $page->field('vision') }}</p></div>
          </article>
        @endif
        @if(mill_filled($page->field('mission')))
          <article class="vmv__item">
            <div class="vmv__icon" aria-hidden="true">@include('partials.icon', ['icon' => 'vmv-mission'])</div>
            <div><h3>Mission</h3><p>{{ $page->field('mission') }}</p></div>
          </article>
        @endif
        @if(mill_filled($page->field('values')))
          <article class="vmv__item">
            <div class="vmv__icon" aria-hidden="true">@include('partials.icon', ['icon' => 'vmv-values'])</div>
            <div>
              <h3>Values</h3>
              <ul>
                @foreach(preg_split('/\r\n|\r|\n/', $page->field('values')) as $line)
                  @if(mill_filled($line))<li>{{ $line }}</li>@endif
                @endforeach
              </ul>
            </div>
          </article>
        @endif
      </aside>
    </div>
  </section>

  @if($page->blocksFor('timeline')->isNotEmpty())
    <section class="section section--surface">
      <div class="container">
        <header class="section__head section__head--center reveal">
          <p class="section__eyebrow">Our Journey</p>
          <h2 class="section__title">Milestones along the way</h2>
          <p class="section__lead">From a Narayanganj weaving shed to a mill that holds spinning, weaving, and finishing on one campus.</p>
        </header>
        <ol class="timeline reveal">
          @foreach($page->blocksFor('timeline') as $item)
            <li class="timeline__item">
              <div class="timeline__dot" aria-hidden="true">@include('partials.icon', ['icon' => $timeIcons[$loop->index] ?? 'time-found'])</div>
              @if(mill_filled($item->year))<p class="timeline__year">{{ $item->year }}</p>@endif
              @if(mill_filled($item->title))<p class="timeline__title">{{ $item->title }}</p>@endif
              @if(mill_filled($item->text))<p>{{ $item->text }}</p>@endif
            </li>
          @endforeach
        </ol>
      </div>
    </section>
  @endif

  @if($page->blocksFor('leaders')->isNotEmpty())
    <section class="section">
      <div class="container">
        <header class="section__head section__head--center reveal">
          <p class="section__eyebrow">Leadership</p>
          <h2 class="section__title">Guided by experience</h2>
          <p class="section__lead">A mill team across commercial, operations, and quality — based between Dhaka and the Narayanganj floor.</p>
        </header>
        <div class="leader-grid reveal">
          @foreach($page->blocksFor('leaders') as $leader)
            <article class="leader-card">
              @if(mill_filled($leader->initials))<div class="leader-card__avatar" aria-hidden="true">{{ $leader->initials }}</div>@endif
              <div class="leader-card__body">
                @if(mill_filled($leader->title))<h3>{{ $leader->title }}</h3>@endif
                @if(mill_filled($leader->role))<p class="leader-card__role">{{ $leader->role }}</p>@endif
                @if(mill_filled($leader->text))<p>{{ $leader->text }}</p>@endif
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>Partner with a focused textile mill</h2>
        <p>Share constructions, finishes, and volumes — the Dhaka team will respond with next steps.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Request Quote</a>
        <a href="{{ route('products') }}" class="btn btn--outline-light">View Products</a>
      </div>
    </div>
  </section>
@endsection
