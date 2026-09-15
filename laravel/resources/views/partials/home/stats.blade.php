@if($page->sectionActive('stats') && $stats->isNotEmpty())
  <section class="stats" aria-label="Company highlights">
    <div class="container stats__grid">
      @foreach($stats as $stat)
        @if(mill_filled($stat->value))
          <div class="stat reveal">
            <div class="stat__icon" aria-hidden="true">@include('partials.icon', ['icon' => $statIcons[$loop->index] ?? 'stat-looms'])</div>
            <div class="stat__copy">
              <div class="stat__value" data-count="{{ $stat->value }}" data-suffix="{{ $stat->suffix }}">0</div>
              <div class="stat__label">{{ $stat->title }}</div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </section>
@endif
