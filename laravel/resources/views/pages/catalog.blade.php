@extends('layouts.site', ['metaTitle' => $catalog['title'].' | '.$site->site_name, 'metaDescription' => $catalog['meta']])
@section('content')
  <section class="weave-hero">
    @if(mill_url($catalog['hero']))<img class="weave-hero__bg" src="{{ mill_url($catalog['hero']) }}" alt="">@endif
    <div class="weave-hero__shade" aria-hidden="true"></div>
    <div class="container weave-hero__inner">
      <div class="weave-hero__copy">
        <p class="weave-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <a href="{{ route('products') }}">Products</a> <span aria-hidden="true">/</span> {{ $catalog['title'] }}</p>
        <h1>{{ $catalog['title'] }}</h1>
      </div>
      <p class="weave-hero__note">
        <span class="weave-hero__rule" aria-hidden="true"></span>
        <span class="weave-hero__mark" aria-hidden="true">
          @if($catalog['mark'] === 'yarn')
            <svg viewBox="0 0 48 48" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 16c4.4-4.8 15.6-4.8 20 0"/><path d="M12 22c5.6-5.2 18.4-5.2 24 0"/><path d="M10 28.5c6.8-5.6 21.2-5.6 28 0"/><path d="M24 29v12"/></svg>
          @elseif($catalog['mark'] === 'finish')
            <svg viewBox="0 0 48 48" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 18h24v18H12z"/><path d="M18 18v-3a6 6 0 0 1 12 0v3"/><path d="M16 27h16"/></svg>
          @else
            <svg viewBox="0 0 48 48" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.45"><path d="M24 7 41 24 24 41 7 24Z"/><path d="M15.5 15.5 32.5 32.5M32.5 15.5 15.5 32.5"/><path d="M24 7v34M7 24h34"/><path d="M24 13.5 34.5 24 24 34.5 13.5 24Z"/></svg>
          @endif
        </span>
        <span>{{ $catalog['note'] }}</span>
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
        @foreach($catalog['filters'] as $filter)
          <div class="filter-accordion is-open">
            <button type="button" class="filter-accordion__btn" aria-expanded="true">{{ $filter['label'] }} <span aria-hidden="true"></span></button>
            <div class="filter-accordion__body">
              @foreach($filter['options'] as $value => $label)
                <label class="filter-check"><input type="checkbox" data-filter-key="{{ $filter['key'] }}" value="{{ $value }}"> {{ $label }} <span data-filter-count="{{ $filter['key'] }}" data-filter-value="{{ $value }}"></span></label>
              @endforeach
            </div>
          </div>
        @endforeach
        <button type="button" class="weave-clear" data-clear-filters>Clear all filters</button>
      </aside>

      <div>
        <div class="weave-toolbar">
          <div class="weave-toolbar__lead">
            <h2>{{ $catalog['title'] }}</h2>
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
                  @if(mill_filled($product->weave))<span>{{ $product->weave }}</span>@elseif(mill_filled($product->count))<span>{{ $product->count }}</span>@endif
                </div>
                <dl class="weave-specs">
                  @foreach(['Count' => $product->count, 'Weave' => $product->weave, 'Finish' => $product->finish, 'Construction' => $product->construction, 'GSM' => $product->gsm] as $label => $value)
                    @if(mill_filled($value))<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endif
                  @endforeach
                </dl>
                <a href="{{ route('contact') }}" class="btn btn--primary weave-card__btn">@include('partials.icon', ['icon' => 'swatch']) {{ $catalog['action'] }}</a>
              </div>
            </article>
          @endforeach
        </div>
        <p class="weave-empty" data-product-empty hidden>{{ $catalog['empty'] }}</p>
      </div>
    </div>
  </section>

  <section class="cta-band reveal">
    <div class="container cta-band__inner">
      <div>
        <h2>{{ $catalog['cta_title'] }}</h2>
        <p>{{ $catalog['cta_text'] }}</p>
      </div>
      <div class="cta-band__contact">
        <a href="{{ route('contact') }}" class="btn btn--outline-light">{{ $catalog['cta_label'] }}</a>
      </div>
    </div>
  </section>
@endsection
