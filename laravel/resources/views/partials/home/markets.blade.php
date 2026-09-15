@if($page->sectionActive('markets') && mill_filled($page->field('markets_title')))
  <section class="section home-markets">
    <div class="container home-split home-split--reverse">
      <div class="home-split__media reveal">
        <img src="{{ mill_url('media/gallery/gallery-5.jpg') }}" alt="Dyeing machines on the wet-processing floor at Islam Textile">
        <p class="home-split__caption">Dyeing floor · Wet processing</p>
      </div>
      <div class="home-split__body reveal">
        <p class="section__eyebrow">Markets We Serve</p>
        <h2 class="section__title section__title--display">{{ $page->field('markets_title') }}</h2>
        @if(mill_filled($page->field('markets_lead')))<p class="section__lead">{{ $page->field('markets_lead') }}</p>@endif
        <div class="markets-grid">
          @foreach($markets as $market)
            <article class="market-chip">
              @if(mill_filled($market->title))<h3>{{ $market->title }}</h3>@endif
              @if(mill_filled($market->text))<p>{{ $market->text }}</p>@endif
            </article>
          @endforeach
        </div>
        <p class="markets-note">Program planning in Dhaka. Mill execution in Narayanganj. Export via Chattogram.</p>
        <div class="btn-group">
          <a href="{{ route('contact') }}" class="btn btn--primary">Request Quote</a>
          <a href="{{ route('products.woven') }}" class="btn btn--outline">Browse Fabrics</a>
        </div>
      </div>
    </div>
  </section>
@endif
