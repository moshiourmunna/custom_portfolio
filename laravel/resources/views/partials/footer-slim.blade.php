<footer class="site-footer site-footer--slim">
  <div class="container footer-slim-inner">
    <div class="footer-brand-row">
      <a href="{{ route('home') }}" class="logo" aria-label="{{ $site->site_name }} home">
        <img class="logo__mark" src="{{ $site->logoUrl() }}" alt="">
        <span class="logo__text"><span class="logo__name">{{ $site->site_name }}</span></span>
      </a>
      <p class="footer-copy">&copy; 2025 {{ $site->site_name }}. All rights reserved.</p>
    </div>
    <div class="footer-legal">
      <a href="{{ route('privacy') }}">Privacy Policy</a>
      <a href="{{ route('terms') }}">Terms &amp; Conditions</a>
      <a href="{{ route('sitemap') }}">Sitemap</a>
    </div>
  </div>
</footer>
