@extends('layouts.site', ['metaTitle' => $page->title.' | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  @php
    $intro = '';
    $sections = [];
    foreach (preg_split("/\n\s*\n/", trim((string) $page->body)) ?: [] as $chunk) {
        $chunk = trim($chunk);
        if ($chunk === '') continue;
        if (preg_match('/^(\d+)\.\s+([^\n]+)(?:\n([\s\S]+))?$/', $chunk, $match)) {
            $sections[] = ['title' => trim($match[2]), 'text' => trim($match[3] ?? '')];
        } elseif ($sections === []) {
            $intro = trim($intro.' '.$chunk);
        }
    }
  @endphp

  @if($kind === 'privacy')
    <section class="section privacy-page">
      <div class="container privacy-layout">
        <div class="privacy-copy">
          <header class="privacy-intro">
            <h1>{{ $page->title }}</h1>
            @if(mill_filled($page->updated_on))<p class="privacy-intro__date">Last updated: {{ $page->updated_on }}</p>@endif
            @if(mill_filled($intro))<p>{{ $intro }}</p>@endif
          </header>

          @php
            $privacy = [
              ['id' => 'collect', 'icon' => '<svg viewBox="0 0 24 24"><rect x="7" y="4.5" width="10" height="15" rx="1.5"/><path d="M9.2 4.5h5.6V7H9.2zM9.2 11h5.6M9.2 14.2h3.6"/></svg>', 'list' => ['Name, company, email, phone, and message content from inquiry or career forms', 'Project information you include with a quote request', 'File attachments uploaded with a quote request, stored only as needed to process the inquiry', 'IP address and browser type in technical logs, for security']],
              ['id' => 'use', 'icon' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="2.6"/><path d="M12 3.2v2.1M12 18.7v2.1M3.2 12h2.1M18.7 12h2.1M5.8 5.8l1.5 1.5M16.7 16.7l1.5 1.5M18.2 5.8l-1.5 1.5M7.3 16.7l-1.5 1.5"/></svg>', 'list' => ['To respond to quotes, applications, and support requests', 'To improve our services', 'To maintain site security', 'To meet legal obligations under Bangladesh law where applicable']],
              ['id' => 'sharing', 'icon' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="2.1"/><circle cx="16" cy="9" r="1.7"/><path d="M4.2 18.2c.5-2.5 2.4-3.9 4.8-3.9s4.3 1.4 4.8 3.9M13.2 14.6c1.3-.4 2.6-.4 3.7.1 1.4.6 2.3 1.9 2.6 3.5"/></svg>', 'list' => []],
              ['id' => 'retention', 'icon' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 1.5"/></svg>', 'list' => []],
              ['id' => 'contact-privacy', 'icon' => '<svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M4 7l8 6 8-6"/></svg>', 'list' => []],
            ];
          @endphp
          @foreach($privacy as $index => $block)
            @php $section = $sections[$index] ?? null; @endphp
            @if($section)
              <article class="privacy-block" id="{{ $block['id'] }}">
                <div class="privacy-block__icon" aria-hidden="true">{!! $block['icon'] !!}</div>
                <div>
                  <h2>{{ $index + 1 }}. {{ $section['title'] }}</h2>
                  @if(mill_filled($section['text']))<p>{{ $section['text'] }}</p>@endif
                  @if($block['list'])
                    <ul>
                      @foreach($block['list'] as $item)<li>{{ $item }}</li>@endforeach
                    </ul>
                  @endif
                  @if($block['id'] === 'contact-privacy' && mill_filled($site->email))
                    <ul>
                      <li>Email: <a href="mailto:{{ $site->email }}">{{ $site->email }}</a></li>
                      @if($site->telHref())<li>Phone: <a href="{{ $site->telHref() }}">{{ $site->phone }}</a></li>@endif
                      <li>Office: Dhaka, Bangladesh</li>
                    </ul>
                  @endif
                </div>
              </article>
            @endif
          @endforeach
        </div>
        <aside class="privacy-nav" data-scrollspy>
          <h2>Quick links</h2>
          <a href="#collect" class="is-active">Information we collect</a>
          <a href="#use">How we use data</a>
          <a href="#sharing">Sharing</a>
          <a href="#retention">Retention</a>
          <a href="#contact-privacy">Contact</a>
          <p class="privacy-nav__also">Also see</p>
          <a href="{{ route('terms') }}">Terms of Use</a>
        </aside>
      </div>
    </section>
  @else
    <section class="terms-hero">
      <img class="terms-hero__bg" src="{{ mill_url('media/gallery/gallery-2.jpg') }}" alt="">
      <div class="container terms-hero__content">
        <h1>{{ $page->title }}</h1>
        @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
      </div>
    </section>
    <section class="section terms-page">
      <div class="container terms-layout">
        <div class="terms-copy">
          @php
            $ids = ['acceptance', 'accuracy', 'ip', 'inquiries', 'liability', 'contact-terms'];
          @endphp
          @foreach($sections as $index => $section)
            <article class="terms-item" id="{{ $ids[$index] ?? 'term-'.$index }}">
              <span class="terms-item__num">{{ $index + 1 }}</span>
              <div>
                <h2>{{ $section['title'] }}</h2>
                @if(mill_filled($section['text']))<p>{{ $section['text'] }}</p>@endif
                @if(($ids[$index] ?? '') === 'contact-terms')
                  <ul>
                    @if(mill_filled($site->email))<li>Email: <a href="mailto:{{ $site->email }}">{{ $site->email }}</a></li>@endif
                    @if($site->telHref())<li>Phone: <a href="{{ $site->telHref() }}">{{ $site->phone }}</a></li>@endif
                    <li>Corporate office: Dhaka, Bangladesh</li>
                    <li>Mill: Narayanganj, Bangladesh</li>
                  </ul>
                @endif
              </div>
            </article>
          @endforeach
        </div>
        <aside class="terms-points" data-scrollspy>
          <div class="terms-points__mark" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M12 3.5l7 2.4v6.2c0 4.2-2.8 7.1-7 8.4-4.2-1.3-7-4.2-7-8.4V5.9l7-2.4z"/><path d="M8.8 12.1l2.1 2.1 4.3-4.4"/></svg>
          </div>
          <h2>Key Points</h2>
          <a href="#acceptance" class="is-active">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5.5 19.2a6.5 6.5 0 0113 0"/></svg>
            <span>Using this website means you agree to these terms.</span>
          </a>
          <a href="#accuracy">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.5h7l4 4V20.5H7z"/><path d="M14 3.5V8h4M9.5 13h5M9.5 16.5h3.5"/></svg>
            <span>Specifications and imagery are confirmed only in a formal quotation.</span>
          </a>
          <a href="#ip">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="10" width="12" height="9" rx="1.5"/><path d="M8.5 10V7.8a3.5 3.5 0 017 0V10"/></svg>
            <span>Do not reproduce site design, text, photography, or brand marks without written permission.</span>
          </a>
          <a href="#inquiries">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 5.5h12v13H6z"/><path d="M8.5 9h7M8.5 12.2h7M8.5 15.4h4"/></svg>
            <span>A form does not create a supply contract until terms are agreed in writing.</span>
          </a>
          <a href="#liability">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3.5l7 2.4v6.2c0 4.2-2.8 7.1-7 8.4-4.2-1.3-7-4.2-7-8.4V5.9l7-2.4z"/></svg>
            <span>Website content is not a substitute for a confirmed quotation.</span>
          </a>
          <a href="#contact-terms">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17" height="13" rx="1.5"/><path d="M4.5 7.2l7.5 5.6 7.5-5.6"/></svg>
            <span>Contact the Dhaka office or the Narayanganj mill.</span>
          </a>
          <p class="terms-points__also">Also see <a href="{{ route('privacy') }}">Privacy Policy</a>.</p>
        </aside>
      </div>
    </section>
  @endif
@endsection
