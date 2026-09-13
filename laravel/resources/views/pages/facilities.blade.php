@extends('layouts.site', ['metaTitle' => 'Facilities & Capacity | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  @php
    $capacityIcons = [
      '<svg viewBox="0 0 24 24"><path d="M8 3.5v17M16 3.5v17"/><path d="M8 6.2c1.7.6 1.7 2.1 0 2.7M8 10.4c1.7.6 1.7 2.1 0 2.7M8 14.6c1.7.6 1.7 2.1 0 2.7"/><path d="M16 6.2c1.7.6 1.7 2.1 0 2.7M16 10.4c1.7.6 1.7 2.1 0 2.7M16 14.6c1.7.6 1.7 2.1 0 2.7"/></svg>',
      '<svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="14" rx="1"/><path d="M3.5 9.5h17M3.5 14.5h17M8.5 5v14M15.5 5v14"/></svg>',
      '<svg viewBox="0 0 24 24"><circle cx="8" cy="8" r="2.3"/><circle cx="16.2" cy="9" r="1.8"/><path d="M3.4 18.5c.6-2.8 2.5-4.1 4.7-4.1s4.1 1.3 4.7 4.1"/><path d="M13.4 18.5c.35-1.9 1.6-3 3.1-3 1.4 0 2.5.7 3 2.1"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M8.5 6.5c1.6 0 2.8 2.2 2.8 5.5s-1.2 5.5-2.8 5.5-2.8-2.2-2.8-5.5 1.2-5.5 2.8-5.5z"/><path d="M11.2 7.4H19v9.2h-7.8"/><path d="M14.6 7.4v9.2M17.4 7.4v9.2"/></svg>',
    ];
    $campusIcons = [
      '<svg viewBox="0 0 24 24"><path d="M4 20h16M6 20V10l6-5 6 5v10"/><path d="M10 20v-5h4v5"/></svg>',
      '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M3 8h13v9H3z"/><path d="M16 11h3.2L21 13.5V17h-5"/><circle cx="7" cy="17.5" r="1.2"/><circle cx="17.2" cy="17.5" r="1.2"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M4 19h16M6 19V8l6-4 6 4v11"/><path d="M10 19v-4h4v4"/></svg>',
    ];
  @endphp
  <section class="facilities-hero">
    <div class="facilities-hero__copy">
      <p class="facilities-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> Facilities</p>
      <p class="section__eyebrow">Facilities &amp; Capacity</p>
      <h1>{{ $page->title }}</h1>
      @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
    </div>
    <div class="facilities-hero__media">
      @if(mill_url($page->image))<img src="{{ mill_url($page->image) }}" alt="Air-jet weaving loom at the Islam Textile mill in Narayanganj">@endif
    </div>
  </section>

  <section class="capacity-bar" aria-label="Capacity highlights">
    <div class="container capacity-bar__grid">
      @foreach($page->blocksFor('figures') as $figure)
        <article class="capacity-bar__item reveal">
          <div class="capacity-bar__icon" aria-hidden="true">{!! $capacityIcons[$loop->index % count($capacityIcons)] !!}</div>
          <div>
            <strong data-count="{{ $figure->value }}" data-suffix="{{ $figure->suffix }}">0</strong>
            <span>{{ $figure->title }}</span>
            @if(mill_filled($figure->meta))<em>{{ $figure->meta }}</em>@endif
          </div>
        </article>
      @endforeach
    </div>
  </section>

  <section class="section facilities-board">
    <div class="container facilities-layout">
      <aside class="facility-list reveal">
        <p class="facility-list__label">Our facilities</p>
        @foreach($page->blocksFor('campus') as $dept)
          <div class="facility-list__item">
            <div class="facility-list__icon" aria-hidden="true">{!! $campusIcons[$loop->index % count($campusIcons)] !!}</div>
            <div>
              @if(mill_filled($dept->title))<h3>{{ $dept->title }}</h3>@endif
              @if(mill_filled($dept->text))<p>{{ $dept->text }}</p>@endif
            </div>
          </div>
        @endforeach
      </aside>
      <div class="dept-grid reveal">
        @foreach($page->blocksFor('dept_cards') as $card)
          <article class="dept-card @if(str_contains(strtolower($card->title), 'lab')) dept-card--lab @endif">
            @if(mill_url($card->image))<img src="{{ mill_url($card->image) }}" alt="{{ $card->text ?: $card->title }}">@endif
            <span class="dept-card__label">{{ $card->title }}</span>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <div class="facilities-foot">
    <div class="container facilities-foot__inner">
      <img src="{{ $site->logoUrl() }}" alt="" width="42" height="42">
      <p>Strong facilities. Reliable capacity. Trusted programs.</p>
    </div>
  </div>

  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>Plan a Mill Visit</h2>
        <p>Buyers and auditors welcome — contact us to schedule a facility tour in Narayanganj.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Schedule Visit</a>
      </div>
    </div>
  </section>
@endsection
