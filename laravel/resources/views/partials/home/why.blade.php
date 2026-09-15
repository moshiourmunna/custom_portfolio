@if($page->sectionActive('why') && mill_filled($page->field('why_title')))
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
