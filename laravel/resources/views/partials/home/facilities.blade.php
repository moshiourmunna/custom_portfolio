@if($page->sectionActive('facilities') && mill_filled($page->field('facilities_title')))
  <section class="section home-facilities">
    <div class="container home-split">
      <div class="home-split__media reveal">
        @if(mill_url($page->field('facilities_image')))
          <img src="{{ mill_url($page->field('facilities_image')) }}" alt="Air-jet weaving loom at the Islam Textile mill in Narayanganj">
        @endif
        <p class="home-split__caption">Narayanganj campus · Weaving floor</p>
      </div>
      <div class="home-split__body reveal">
        <p class="section__eyebrow">Facilities &amp; Capacity</p>
        <h2 class="section__title section__title--display">{{ $page->field('facilities_title') }}</h2>
        @if(mill_filled($page->field('facilities_lead')))<p class="section__lead">{{ $page->field('facilities_lead') }}</p>@endif
        <div class="home-split__metrics" aria-label="Mill capacity">
          @foreach($figures as $figure)
            <div><strong data-count="{{ $figure->value }}" data-suffix="{{ $figure->suffix }}">0</strong><span>{{ $figure->title }}</span></div>
          @endforeach
        </div>
        <ul class="campus-points">
          @foreach($bullets as $bullet)
            <li><strong>{{ $bullet->title }}</strong><span>{{ $bullet->text }}</span></li>
          @endforeach
        </ul>
        <p class="campus-line">Spinning · Weaving · Processing · Laboratory · Warehouse · ETP</p>
        <div class="btn-group">
          <a href="{{ route('facilities') }}" class="btn btn--primary">Explore Facilities</a>
          <a href="{{ route('contact') }}" class="btn btn--outline">Schedule a Visit</a>
        </div>
      </div>
    </div>
  </section>
@endif
