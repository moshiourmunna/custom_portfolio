@extends('layouts.site', ['metaTitle' => 'Page not found | Islam Textile'])
@section('content')
  <section class="not-found">
    <div>
      <p class="not-found__code">404</p>
      <h1>Page Not Found</h1>
      <p>This page is missing or has moved. Return home or contact our Dhaka team for fabric program support.</p>
      <div class="btn-group" style="justify-content:center">
        <a href="{{ route('home') }}" class="btn btn--primary">Go Home</a>
        <a href="{{ route('contact') }}" class="btn btn--outline">Contact Us</a>
      </div>
    </div>
  </section>
@endsection
