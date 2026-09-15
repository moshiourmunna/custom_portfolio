@if($page->sectionActive('quality') && mill_filled($page->field('quality_title')))
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
