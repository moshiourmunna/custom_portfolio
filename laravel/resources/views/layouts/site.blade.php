@php
  $site = $site ?? \App\Models\Setting::current();
  $title = $metaTitle ?? $site->meta_title ?? $site->site_name;
  $description = $metaDescription ?? $site->meta_description;
  $canonicalBase = rtrim($site->canonical_base ?: url('/'), '/');
  $canonical = $canonical ?? $canonicalBase.request()->getPathInfo();
  $og = mill_url($ogImage ?? $site->og_image);
  $nav = [
    ['Home', route('home'), request()->routeIs('home')],
    ['About Us', route('about'), request()->routeIs('about')],
    ['Products', route('products'), request()->routeIs('products*')],
    ['Facilities & Capacity', route('facilities'), request()->routeIs('facilities')],
    ['Quality', route('quality'), request()->routeIs('quality')],
    ['Sustainability', route('sustainability'), request()->routeIs('sustainability')],
    ['Contact Us', route('contact'), request()->routeIs('contact') || request()->routeIs('quote.success')],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  @if(mill_filled($description))<meta name="description" content="{{ $description }}">@endif
  <link rel="canonical" href="{{ $canonical }}">
  <meta property="og:title" content="{{ $title }}">
  @if(mill_filled($description))<meta property="og:description" content="{{ $description }}">@endif
  <meta property="og:url" content="{{ $canonical }}">
  <meta property="og:type" content="{{ $ogType ?? 'website' }}">
  @if($og)<meta property="og:image" content="{{ $og }}">@endif
  <meta property="og:locale" content="en_BD">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $site->faviconUrl() }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/theme-override.css') }}">
  <style>{!! \App\Support\Mill::themeCss($site) !!}</style>
  <script defer src="{{ asset('assets/js/main.js') }}"></script>
  @stack('head')
</head>
<body>
  <a href="#main" class="skip-link">Skip to content</a>
  <header class="site-header">
    <div class="container site-header__inner">
      <a href="{{ route('home') }}" class="logo" aria-label="{{ $site->site_name }} home">
        <img class="logo__mark" src="{{ $site->logoUrl() }}" alt="">
        <span class="logo__text">
          <span class="logo__name">{{ $site->site_name }}</span>
          @if(mill_filled($site->tagline))<span class="logo__tag">{{ $site->tagline }}</span>@endif
        </span>
      </a>
      <div class="site-header__nav-wrap">
        <nav class="nav" aria-label="Main">
          @foreach($nav as [$label, $href, $active])
            <a href="{{ $href }}" @class(['is-active' => $active])>{{ $label }}</a>
          @endforeach
        </nav>
      </div>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Toggle menu"><span></span></button>
    </div>
    <nav id="mobile-nav" class="mobile-nav" aria-label="Mobile">
      @foreach($nav as [$label, $href, $active])
        <a href="{{ $href }}" @class(['is-active' => $active])>{{ $label }}</a>
      @endforeach
    </nav>
  </header>

  <main id="main">
    @yield('content')
  </main>

  @if(($footer ?? '') === 'slim')
    @include('partials.footer-slim')
  @else
    @include('partials.footer')
  @endif
  @if(mill_filled($site->ga))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $site->ga }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $site->ga }}');</script>
  @endif
</body>
</html>
