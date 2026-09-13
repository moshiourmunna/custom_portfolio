@extends('layouts.site', ['metaTitle' => $page->title.' | '.$site->site_name])
@section('content')
  <section class="not-found">
    <div>
      <img class="logo__mark" src="{{ $site->logoUrl() }}" alt="" style="width:72px;height:auto;margin:0 auto 1.25rem;display:block">
      <h1>{{ $page->title }}</h1>
      @if(mill_filled($page->body))<p>{{ $page->body }}</p>@endif
      <div class="btn-group" style="justify-content:center">
        <a href="{{ route('home') }}" class="btn btn--primary">Back to Home</a>
        <a href="{{ route('products') }}" class="btn btn--outline">Browse Products</a>
      </div>
    </div>
  </section>
@endsection
