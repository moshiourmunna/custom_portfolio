<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="{{ route('home') }}" class="logo" aria-label="{{ $site->site_name }} home">
          <img class="logo__mark" src="{{ $site->logoUrl() }}" alt="">
          <span class="logo__text">
            <span class="logo__name">{{ $site->site_name }}</span>
            @if(mill_filled($site->tagline))<span class="logo__tag">{{ $site->tagline }}</span>@endif
          </span>
        </a>
        @if(mill_filled($site->footer_blurb))<p>{{ $site->footer_blurb }}</p>@endif
      </div>
      <div class="footer-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('products') }}">Products</a></li>
          <li><a href="{{ route('facilities') }}">Facilities &amp; Capacity</a></li>
          <li><a href="{{ route('quality') }}">Quality</a></li>
          <li><a href="{{ route('sustainability') }}">Sustainability</a></li>
          <li><a href="{{ route('contact') }}">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="{{ route('process') }}">Process</a></li>
          <li><a href="{{ route('gallery') }}">Gallery</a></li>
          <li><a href="{{ route('news') }}">News</a></li>
          <li><a href="{{ route('careers') }}">Careers</a></li>
          <li><a href="{{ route('privacy') }}">Privacy</a></li>
          <li><a href="{{ route('terms') }}">Terms</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <ul class="footer-contact">
          <li>Corporate Office: Dhaka, Bangladesh</li>
          <li>Mill: Narayanganj, Bangladesh</li>
          @if($site->telHref())<li><a href="{{ $site->telHref() }}">{{ $site->phone }}</a></li>@endif
          @if(mill_filled($site->email))<li><a href="mailto:{{ $site->email }}">{{ $site->email }}</a></li>@endif
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} {{ $site->site_name }}. All rights reserved.</p>
      <p>Made in Bangladesh</p>
    </div>
  </div>
</footer>
