@extends('layouts.site', ['metaTitle' => $page->title.' | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  @php
    $stepIcons = [
      '<svg viewBox="0 0 24 24"><path d="M12 4c3 0 5 1.6 5 3.5S15 11 12 11 7 9.4 7 7.5 9 4 12 4z"/><path d="M8 10.5v6.2c0 1.6 1.8 2.8 4 2.8s4-1.2 4-2.8v-6.2"/></svg>',
      '<svg viewBox="0 0 24 24"><ellipse cx="8" cy="12" rx="3.2" ry="5"/><ellipse cx="16" cy="12" rx="3.2" ry="5"/><path d="M8 7h8M8 17h8"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16M8 4v16M16 4v16"/></svg>',
      '<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"/><path d="M16 16l4 4"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M12 3c2.8 3.4 4.2 6 4.2 8.4a4.2 4.2 0 11-8.4 0C7.8 9 9.2 6.4 12 3z"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M3 8h12v8H3z"/><path d="M15 11h3.2L21 14v2h-6"/><circle cx="7" cy="17.2" r="1.3"/><circle cx="17.2" cy="17.2" r="1.3"/></svg>',
    ];
    $statIcons = [
      '<svg viewBox="0 0 24 24"><path d="M8 4h8v16H8z"/><path d="M8 9h8M8 14h8"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M8 7h8v10H8z"/><path d="M10 7V5h4v2"/><path d="M12 11v3"/></svg>',
      '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M8 4h8v16H8z"/><path d="M10 9h4M10 13h4M10 17h2"/></svg>',
    ];
  @endphp
  <section class="process-hero">
    <div class="process-hero__media">
      @if(mill_url($page->image))<img class="process-hero__bg" src="{{ mill_url($page->image) }}" alt="">@endif
    </div>
    <div class="container process-hero__copy">
      <div class="process-hero__text">
        @if(mill_filled($page->eyebrow))<p class="section__eyebrow">{{ $page->eyebrow }}</p>@endif
        <h1>Our manufacturing<br> process</h1>
        @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
      </div>
    </div>
  </section>

  <section class="section process-flow">
    <div class="container">
      <ol class="process-steps reveal">
        @foreach($page->blocksFor('stages') as $stage)
          <li class="process-step">
            <div class="process-step__mark">
              <span class="process-step__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="process-step__icon" aria-hidden="true">{!! $stepIcons[$loop->index % count($stepIcons)] !!}</div>
            </div>
            @if(mill_filled($stage->title))<h2>{{ $stage->title }}</h2>@endif
            @if(mill_url($stage->image))<div class="process-step__img"><img src="{{ mill_url($stage->image) }}" alt="{{ $stage->title }}"></div>@endif
            @if(mill_filled($stage->text))<p>{{ $stage->text }}</p>@endif
          </li>
        @endforeach
      </ol>
    </div>
  </section>

  <section class="process-band">
    <div class="container process-band__inner reveal">
      @if(mill_filled($page->field('quote')))
        <blockquote class="process-quote"><p>{{ $page->field('quote') }}</p></blockquote>
      @endif
      <ul class="process-stats" aria-label="Process highlights">
        <li>
          <div class="process-stats__icon" aria-hidden="true">{!! $statIcons[0] !!}</div>
          <div><strong data-count="6">6</strong><span>Process stages</span></div>
        </li>
        <li>
          <div class="process-stats__icon" aria-hidden="true">{!! $statIcons[1] !!}</div>
          <div><strong data-count="100" data-suffix="%">100%</strong><span>Lot traceability</span></div>
        </li>
        <li>
          <div class="process-stats__icon" aria-hidden="true">{!! $statIcons[2] !!}</div>
          <div><strong data-count="24" data-suffix="/7">24/7</strong><span>Mill operations</span></div>
        </li>
        <li>
          <div class="process-stats__icon" aria-hidden="true">{!! $statIcons[3] !!}</div>
          <div><strong data-count="4">4</strong><span>QA checkpoints</span></div>
        </li>
      </ul>
    </div>
  </section>

  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>Tour Our Mill</h2>
        <p>Schedule a facility visit or virtual walkthrough with our Narayanganj production team.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Arrange a Visit</a>
      </div>
    </div>
  </section>
@endsection
