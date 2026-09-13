@extends('layouts.site', ['metaTitle' => $page->title.' | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  @php
    $pillarIcons = [
      '<svg viewBox="0 0 24 24"><path d="M12 3c4 4 6 7 6 10a6 6 0 11-12 0c0-3 2-6 6-10z"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M9 3h6"/><path d="M10 3v5.5L6.2 17.2A2.2 2.2 0 008.2 20.5h7.6a2.2 2.2 0 002-3.3L14 8.5V3"/><path d="M8.2 14.5h7.6"/></svg>',
      '<svg viewBox="0 0 24 24"><path d="M4 12a8 8 0 0113.2-6"/><path d="M16.2 3.2v4.2h-4.2"/><path d="M20 12a8 8 0 01-13.2 6"/><path d="M7.8 20.8v-4.2h4.2"/></svg>',
    ];
    $pillarLists = [
      ['Primary and secondary ETP', 'Metered process water', 'Reuse where it is feasible'],
      ['Heat recovery on stenters', 'LED retrofit on the floor', 'Compressed-air leak audits'],
      ['Approved chemical inventory', 'SDS on file', 'Random RSL testing'],
      ['Segregated scrap streams', 'Packing reduction', 'Vendor take-back where available'],
    ];
    $metricIcons = [
      $pillarIcons[0],
      $pillarIcons[1],
      $pillarIcons[2],
      '<svg viewBox="0 0 24 24"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>',
    ];
  @endphp
  <section class="sustain-hero">
    <div class="sustain-hero__copy">
      <p class="sustain-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> Sustainability</p>
      <p class="section__eyebrow">Responsibility</p>
      <h1>{{ $page->title }}</h1>
      @if(mill_filled($page->line))<p class="sustain-hero__line">{{ $page->line }}</p>@endif
      @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
    </div>
    <div class="sustain-hero__media">
      @if(mill_url($page->image))<img src="{{ mill_url($page->image) }}" alt="Effluent treatment plant at the Narayanganj campus">@endif
    </div>
  </section>

  <section class="scope-bar">
    <div class="container scope-bar__inner">
      <div class="scope-bar__left">
        <div class="scope-bar__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M12 21s-7-4.5-7-10a4 4 0 018 0 4 4 0 018 0c0 5.5-7 10-7 10z"/></svg>
        </div>
        <div>
          <strong>Our scope</strong>
          <p>Wet processing, utilities, waste streams, and associate safety across the integrated mill. Spinning. Weaving. Finishing.</p>
        </div>
      </div>
      <p class="scope-bar__note">Annual targets are set for water, energy, and chemical compliance. Progress is shared with buyers who request the profile.</p>
    </div>
  </section>

  <section class="section sustain-pillars">
    <div class="container">
      <div class="pillars reveal">
        @foreach($page->blocksFor('pillars') as $pillar)
          <article class="pillar">
            <div class="pillar__icon" aria-hidden="true">{!! $pillarIcons[$loop->index % count($pillarIcons)] !!}</div>
            <h2>{{ $pillar->title }}</h2>
            @if(mill_filled($pillar->text))<p>{{ $pillar->text }}</p>@endif
            <ul>
              @foreach($pillarLists[$loop->index % count($pillarLists)] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
            @if(mill_filled($pillar->href) && mill_filled($pillar->link_label))
              <a href="{{ $pillar->href }}" class="link-more">{{ $pillar->link_label }} →</a>
            @endif
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="commitment">
    <div class="container commitment__inner">
      <div class="commitment__copy">
        <p class="section__eyebrow">Our commitment</p>
        <h2>{{ $page->commitment_title ?: 'Measurable progress. Continuous improvement.' }}</h2>
        @if(mill_filled($page->body))<p>{{ $page->body }}</p>@endif
      </div>
      <div class="commitment__metrics">
        <div class="commitment__metric">
          <div class="commitment__metric-icon" aria-hidden="true">{!! $metricIcons[0] !!}</div>
          <strong>Water intensity</strong>
          <span>Tracked per meter</span>
        </div>
        <div class="commitment__metric">
          <div class="commitment__metric-icon" aria-hidden="true">{!! $metricIcons[1] !!}</div>
          <strong>Energy intensity</strong>
          <span>kWh per meter</span>
        </div>
        <div class="commitment__metric">
          <div class="commitment__metric-icon" aria-hidden="true">{!! $metricIcons[2] !!}</div>
          <strong>Chemistry</strong>
          <span>MRSL screening</span>
        </div>
        <div class="commitment__metric">
          <div class="commitment__metric-icon" aria-hidden="true">{!! $metricIcons[3] !!}</div>
          <strong>Audit readiness</strong>
          <span>Documents on request</span>
        </div>
      </div>
    </div>
    <div class="container commitment__foot">
      <p>Sustainability is a mill practice. We share the record with buyers who ask for it.</p>
      <a href="{{ route('contact') }}" class="btn btn--outline-light">Request the profile</a>
    </div>
  </section>
@endsection
