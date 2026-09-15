@if($page->sectionActive('cta') && mill_filled($page->field('cta_title')))
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
