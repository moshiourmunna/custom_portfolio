@extends('layouts.site', ['metaTitle' => 'Woven Fabric | '.$site->site_name, 'metaDescription' => 'Greige and finished cotton fabrics woven on air-jet and rapier looms.'])
@section('content')
  <section class="weave-hero">
    <img class="weave-hero__bg" src="{{ mill_url('media/products/product-3.jpg') }}" alt="">
    <div class="weave-hero__shade" aria-hidden="true"></div>
    <div class="container weave-hero__inner">
      <div class="weave-hero__copy">
        <p class="weave-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <a href="{{ route('products') }}">Products</a> <span aria-hidden="true">/</span> Woven Fabric</p>
        <h1>Woven Fabric</h1>
      </div>
      <p class="weave-hero__note">
        <span class="weave-hero__rule" aria-hidden="true"></span>
        <span class="weave-hero__mark" aria-hidden="true">
          <svg viewBox="0 0 48 48" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.45"><path d="M24 7 41 24 24 41 7 24Z"/><path d="M15.5 15.5 32.5 32.5M32.5 15.5 15.5 32.5"/><path d="M24 7v34M7 24h34"/><path d="M24 13.5 34.5 24 24 34.5 13.5 24Z"/></svg>
        </span>
        <span>Cotton greige and finished woven fabrics — filter by count, weave, and finish for your next program.</span>
      </p>
    </div>
  </section>

  <section class="section weave-catalog">
    <div class="container products-layout">
      <aside class="filters-panel" aria-label="Product filters">
        <div class="filters-panel__head">
          <h2>Filters</h2>
          <button type="button" class="filter-clear" data-clear-filters>Clear All</button>
        </div>
        <div class="filter-accordion is-open">
          <button type="button" class="filter-accordion__btn" aria-expanded="true">Count (yarn count) <span aria-hidden="true"></span></button>
          <div class="filter-accordion__body">
            <label class="filter-check"><input type="checkbox" data-filter-key="count" value="fine"> Fine (Ne 50+) <span data-filter-count="count" data-filter-value="fine"></span></label>
            <label class="filter-check"><input type="checkbox" data-filter-key="count" value="medium"> Medium (Ne 30–40) <span data-filter-count="count" data-filter-value="medium"></span></label>
            <label class="filter-check"><input type="checkbox" data-filter-key="count" value="coarse"> Coarse (Ne ≤20) <span data-filter-count="count" data-filter-value="coarse"></span></label>
          </div>
        </div>
        <div class="filter-accordion is-open">
          <button type="button" class="filter-accordion__btn" aria-expanded="true">Weave <span aria-hidden="true"></span></button>
          <div class="filter-accordion__body">
            @foreach(['poplin' => 'Poplin', 'twill' => 'Twill', 'oxford' => 'Oxford', 'plain' => 'Plain'] as $value => $label)
              <label class="filter-check"><input type="checkbox" data-filter-key="weave" value="{{ $value }}"> {{ $label }} <span data-filter-count="weave" data-filter-value="{{ $value }}"></span></label>
            @endforeach
          </div>
        </div>
        <div class="filter-accordion is-open">
          <button type="button" class="filter-accordion__btn" aria-expanded="true">Finish <span aria-hidden="true"></span></button>
          <div class="filter-accordion__body">
            @foreach(['greige' => 'Greige', 'rfd' => 'RFD', 'dyed' => 'Dyed', 'finished' => 'Soft finished'] as $value => $label)
              <label class="filter-check"><input type="checkbox" data-filter-key="finish" value="{{ $value }}"> {{ $label }} <span data-filter-count="finish" data-filter-value="{{ $value }}"></span></label>
            @endforeach
          </div>
        </div>
        <button type="button" class="weave-clear" data-clear-filters>Clear all filters</button>
      </aside>

      <div>
        <div class="weave-toolbar">
          <div class="weave-toolbar__lead">
            <h2>Woven fabric</h2>
            <p data-product-status>Showing {{ $products->count() }} products</p>
          </div>
          <div class="weave-toolbar__tools">
            <label class="weave-sort">
              <span>Sort by:</span>
              <select data-product-sort aria-label="Sort products">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name">Name A–Z</option>
              </select>
            </label>
            <div class="view-toggle" role="group" aria-label="View mode">
              <button type="button" class="is-active" data-view="grid" aria-label="Grid view">@include('partials.icon', ['icon' => 'view-grid'])</button>
              <button type="button" data-view="list" aria-label="List view">@include('partials.icon', ['icon' => 'view-list'])</button>
            </div>
          </div>
        </div>
        <div class="weave-grid reveal" data-product-grid>
          @foreach($products as $product)
            <article class="weave-card" data-product data-count="{{ $product->filter_count }}" data-weave="{{ strtolower($product->weave ?? '') }}" data-finish="{{ strtolower($product->finish ?? '') }}" data-name="{{ $product->title }}" data-date="{{ $product->updated_on?->format('Y-m-d') }}">
              <div class="weave-card__media">
                @if(mill_url($product->image))<img src="{{ mill_url($product->image) }}" alt="{{ $product->title }}">@endif
                <button type="button" class="weave-card__wish" data-wish aria-pressed="false" aria-label="Save {{ $product->title }}">@include('partials.icon', ['icon' => 'wish'])</button>
              </div>
              <div class="weave-card__body">
                <div class="weave-card__head">
                  <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a></h3>
                  @if(mill_filled($product->weave))<span>{{ $product->weave }}</span>@endif
                </div>
                <dl class="weave-specs">
                  @foreach(['Count' => $product->count, 'Weave' => $product->weave, 'Finish' => $product->finish, 'Construction' => $product->construction, 'GSM' => $product->gsm] as $label => $value)
                    @if(mill_filled($value))<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endif
                  @endforeach
                </dl>
                <a href="{{ route('contact') }}" class="btn btn--primary weave-card__btn">@include('partials.icon', ['icon' => 'swatch']) Request swatch</a>
              </div>
            </article>
          @endforeach
        </div>
        <p class="weave-empty" data-product-empty hidden>No constructions match these filters. Clear them, or request a custom program below.</p>
      </div>
    </div>
  </section>

  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>Can't Find Your Construction?</h2>
        <p>Share your spec sheet — our Dhaka merchandising team will review feasibility.</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">Request Custom Program</a>
      </div>
    </div>
  </section>
@endsection
