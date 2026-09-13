@extends('layouts.site', [
  'metaTitle' => $product->meta_title ?: $product->title.' | '.$site->site_name,
  'metaDescription' => $product->meta_description ?: $product->summary,
  'ogImage' => $product->image,
  'ogType' => 'product',
])
@section('content')
  @php
    $specIcons = ['time-spin', 'step-weave', 'cert-shield', 'trust-export', 'cert-process', 'trust-clock'];
    $useIcons = ['trust-export', 'cert-shield', 'time-globe', 'step-weave'];
  @endphp
  <section class="section">
    <div class="container">
      <p class="page-hero__crumb" style="margin-bottom:1.5rem"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('products') }}">Products</a> @if($product->category) / <a href="{{ $product->category->href }}">{{ $product->category->name }}</a> @endif / {{ $product->title }}</p>
      <div class="pdp reveal">
        <div class="pdp-gallery">
          <div class="pdp-gallery__main">
            @if(mill_url($product->image))<img src="{{ mill_url($product->image) }}" alt="{{ $product->title }}" data-gallery-main>@endif
          </div>
          @if($product->images->isNotEmpty())
            <div class="pdp-thumbs">
              <button type="button" class="pdp-thumbs__nav" data-thumbs-prev aria-label="Previous thumbnails">‹</button>
              <div class="pdp-thumbs__track">
                @foreach($product->images as $image)
                  <button type="button" class="thumb @if($loop->first) is-active @endif" data-gallery-thumb="{{ mill_url($image->path) }}" aria-label="View image {{ $loop->iteration }}">
                    <img src="{{ mill_url($image->path) }}" alt="">
                  </button>
                @endforeach
              </div>
              <button type="button" class="pdp-thumbs__nav" data-thumbs-next aria-label="Next thumbnails">›</button>
            </div>
          @endif
        </div>
        <div>
          @if(mill_filled($product->weave))<span class="pdp-tag">{{ $product->weave }}</span>@endif
          <h1>{{ $product->title }}</h1>
          @if(mill_filled($product->summary))<p class="pdp__sub">{{ $product->summary }}</p>@endif
          <div class="spec-rows">
            @php $specRows = collect(['Count' => $product->count, 'Weave' => $product->weave, 'Finish' => $product->finish, 'Construction' => $product->construction, 'GSM' => $product->gsm])->filter(fn ($value) => mill_filled($value)); @endphp
            @foreach($specRows as $label => $value)
              <div class="spec-row">
                <div class="spec-row__icon" aria-hidden="true">@include('partials.icon', ['icon' => $specIcons[$loop->index % count($specIcons)]])</div>
                <span class="spec-row__label">{{ $label }}</span>
                <span>{{ $value }}</span>
              </div>
            @endforeach
            @foreach($product->specs->where('label', '!=', 'End use') as $spec)
              @if(mill_filled($spec->value))
                <div class="spec-row">
                  <div class="spec-row__icon" aria-hidden="true">@include('partials.icon', ['icon' => 'cert-doc'])</div>
                  <span class="spec-row__label">{{ $spec->label }}</span>
                  <span>{{ $spec->value }}</span>
                </div>
              @endif
            @endforeach
          </div>
          @if($product->specs->where('label', 'End use')->isNotEmpty())
            <p class="section__eyebrow">End Uses</p>
            <div class="end-uses">
              @foreach($product->specs->where('label', 'End use') as $use)
                <div>
                  <div class="end-uses__icon" aria-hidden="true">@include('partials.icon', ['icon' => $useIcons[$loop->index % count($useIcons)]])</div>
                  <span>{{ $use->value }}</span>
                </div>
              @endforeach
            </div>
          @endif
          @if(mill_filled($product->description))<p>{{ $product->description }}</p>@endif
          <div class="btn-group">
            <a href="{{ route('contact') }}" class="btn btn--primary">Request Quote</a>
            <a href="{{ route('contact') }}" class="btn btn--outline">Order Swatches</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  @if($related->isNotEmpty())
    <section class="section section--surface">
      <div class="container">
        <header class="section__head reveal">
          <p class="section__eyebrow">Related</p>
          <h2 class="section__title">More Woven Options</h2>
        </header>
        <div class="grid-3 reveal">
          @foreach($related as $item)
            <article class="card-media">
              @if(mill_url($item->image))<div class="card-media__img"><img src="{{ mill_url($item->image) }}" alt="{{ $item->title }}"></div>@endif
              <div class="card-media__body">
                <h3>{{ $item->title }}</h3>
                @if(mill_filled($item->summary))<p>{{ $item->summary }}</p>@endif
                <a href="{{ route('products.show', $item->slug) }}" class="link-more">View fabric →</a>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif
  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>Ready to Source This Fabric?</h2>
        <p>Send target shade, quantity, and delivery window — our Dhaka team responds within one business day.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Request Quote</a>
      </div>
    </div>
  </section>
@endsection
