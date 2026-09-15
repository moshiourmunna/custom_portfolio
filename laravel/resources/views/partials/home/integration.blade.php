@if($page->sectionActive('integration') && mill_filled($page->field('integration_title')))
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
