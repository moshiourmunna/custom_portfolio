@extends('layouts.site', ['metaTitle' => $page->meta_title, 'metaDescription' => $page->meta_description, 'ogImage' => $page->image])
@section('content')
  @php
    $cards = [
      'yarn' => ['Combed and carded ring-spun cotton yarn in counts suited for shirting, sheeting, and industrial woven applications.', 'Explore yarn', route('products.yarn'), '<svg viewBox="0 0 24 24"><path d="M8 7.5c2.2-2.4 5.8-2.4 8 0"/><path d="M7 10.5c2.8-2.6 7.2-2.6 10 0"/><path d="M6.2 13.8c3.4-2.8 8.2-2.8 11.6 0"/><path d="M12 14.2v6.3"/></svg>'],
      'woven' => ['Greige and finished cotton fabrics woven on air-jet and rapier looms — poplin, twill, oxford, and plain constructions.', 'Explore woven fabric', route('products.woven'), '<svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16M8 4v16M16 4v16"/></svg>'],
      'finished' => ['Reactive and pigment dyeing, mercerization, sanforizing, and soft finishes for export-ready cotton fabric.', 'Explore finished fabric', route('products.finished'), '<svg viewBox="0 0 24 24"><path d="M6 8h12v10H6z"/><path d="M9 8V6.5A3 3 0 0115 6.5V8"/><path d="M8 13h8"/></svg>'],
    ];
  @endphp
  <section class="range-hero">
    @if(mill_url($page->image))<img class="range-hero__bg" src="{{ mill_url($page->image) }}" alt="">@endif
    <div class="container range-hero__content">
      <div class="range-hero__copy">
        @if(mill_filled($page->eyebrow))<p class="range-hero__kicker">{{ $page->eyebrow }}</p>@endif
        <h1>From yarn to<br> finished fabric.</h1>
        @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
      </div>
      <ul class="range-hero__points">
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 7.5c2.2-2.4 5.8-2.4 8 0"/><path d="M7 10.5c2.8-2.6 7.2-2.6 10 0"/><path d="M6.2 13.8c3.4-2.8 8.2-2.8 11.6 0"/><path d="M12 14.2v6.3"/></svg><span>Ring-spun yarn</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16M8 4v16M16 4v16"/></svg><span>Woven fabrics</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8h12v10H6z"/><path d="M9 8V6.5A3 3 0 0115 6.5V8"/><path d="M8 13h8"/></svg><span>Mill finishing</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4.5h10v15H7z"/><path d="M9.5 8.5h5M9.5 12h5M9.5 15.5h3"/></svg><span>Buyer specs</span></li>
      </ul>
    </div>
  </section>

  <section class="section range-page">
    <div class="container range-grid">
      @foreach($categories as $category)
        @php $card = $cards[$category->slug] ?? null; @endphp
        <article class="range-card" id="{{ $category->slug === 'finished' ? 'finishing' : $category->slug }}">
          @if(mill_url($category->image))
            <div class="range-card__media">
              <img src="{{ mill_url($category->image) }}" alt="{{ $category->name }}">
              @if($card)<span class="range-card__badge" aria-hidden="true">{!! $card[3] !!}</span>@endif
            </div>
          @endif
          <div class="range-card__body">
            <h2>{{ $category->name }}</h2>
            <p>{{ $card[0] ?? $category->text }}</p>
            <a href="{{ $card[2] ?? $category->href }}">{{ $card[1] ?? 'Explore' }} <span aria-hidden="true">&rarr;</span></a>
          </div>
        </article>
      @endforeach
    </div>
  </section>

  <section class="range-band">
    <div class="container range-band__grid">
      <div class="range-band__item"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z"/></svg><div><h2>Export ready</h2><p>Batch documentation and packing to buyer specs.</p></div></div>
      <div class="range-band__item"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 1.5"/></svg><div><h2>Reliable lead times</h2><p>Integrated mill control from yarn to finish.</p></div></div>
      <div class="range-band__item"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.2 4.2L19 7.5"/></svg><div><h2>Consistent quality</h2><p>In-line inspection and laboratory testing.</p></div></div>
      <div class="range-band__item"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6.5-4.2-6.5-9.2a3.7 3.7 0 017.4 0 3.7 3.7 0 017.4 0C20.3 16.8 12 21 12 21z"/></svg><div><h2>Bangladesh made</h2><p>Dhaka office and the Narayanganj mill.</p></div></div>
    </div>
  </section>

  <section class="cta-band">
    <div class="container cta-band__inner">
      <div>
        <h2>Need Custom Specifications?</h2>
        <p>Send us your target construction, GSM, and finish — we will propose a mill program.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Request Quote</a>
      </div>
    </div>
  </section>
@endsection
