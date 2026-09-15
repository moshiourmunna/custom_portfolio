@if($page->sectionActive('careers') && mill_filled($page->field('careers_title')))
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
