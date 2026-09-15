@if($page->sectionActive('products') && $products->isNotEmpty())
  <section class="home-products">
    <div class="container">
      <div class="home-products__grid">
        @foreach($products as $item)
          <article class="home-products__item reveal" @if($loop->first) id="yarn" @endif @if($loop->last) id="finishing" @endif>
            <div class="home-products__icon" aria-hidden="true">@include('partials.icon', ['icon' => $productIcons[$loop->index] ?? 'product-weave'])</div>
            <div class="home-products__body">
              @if(mill_filled($item->title))<h3>{{ $item->title }}</h3>@endif
              @if(mill_filled($item->text))<p>{{ $item->text }}</p>@endif
              @if(mill_filled($productHrefs[$item->title] ?? $item->href))<a href="{{ $productHrefs[$item->title] ?? $item->href }}" class="link-more link-more--serif">View Products →</a>@endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
