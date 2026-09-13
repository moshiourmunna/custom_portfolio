@extends('layouts.site', ['metaTitle' => $page->title.' | '.$site->site_name, 'metaDescription' => $page->lead])
@section('content')
  <section class="contact-hero">
    @if(mill_url($page->image))<img class="contact-hero__bg" src="{{ mill_url($page->image) }}" alt="">@endif
    <div class="container contact-hero__content">
      <p class="contact-hero__crumb"><a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> Contact</p>
      <h1>{{ $page->title }}</h1>
      @if(mill_filled($page->lead))<p>{{ $page->lead }}</p>@endif
    </div>
  </section>

  <section class="section contact-board">
    <div class="container contact-board__grid">
      <aside class="contact-card">
        <header class="contact-card__head">
          @if(mill_filled($page->card_title))<h2>{{ $page->card_title }}</h2>@endif
          @if(mill_filled($page->card_lead))<p>{{ $page->card_lead }}</p>@endif
        </header>

        <div class="contact-places">
          @if(mill_filled($site->office_address))
            <article class="contact-place">
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-10a7 7 0 10-14 0c0 4.8 7 10 7 10z"/><circle cx="12" cy="11" r="2.2"/></svg></div>
              <div>
                <h3>Office</h3>
                <p>{{ $site->office_address }}</p>
              </div>
            </article>
          @endif
          @if(mill_filled($site->mill_address))
            <article class="contact-place">
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20h16M6 20V9l6-5 6 5v11"/><path d="M10 20v-5h4v5"/></svg></div>
              <div>
                <h3>Mill</h3>
                <p>{{ $site->mill_address }}</p>
                <p class="contact-place__note">Spinning, weaving, dyeing &amp; finishing</p>
              </div>
            </article>
          @endif
        </div>

        <div class="contact-map" data-cms-field="contact-map" data-office-pin="{{ $site->office_pin }}" data-mill-pin="{{ $site->mill_pin }}">
          <div class="contact-map__stage">
            <div class="contact-map__art" data-map-fallback aria-hidden="true">
              <svg viewBox="0 0 640 360" preserveAspectRatio="xMidYMid slice">
                <g fill="#c5dfd8">
                  <rect x="36" y="28" width="118" height="72" rx="8"/>
                  <rect x="36" y="148" width="118" height="58" rx="8"/>
                  <rect x="36" y="248" width="118" height="78" rx="8"/>
                  <rect x="214" y="28" width="150" height="72" rx="8"/>
                  <rect x="214" y="248" width="72" height="78" rx="8"/>
                  <rect x="302" y="248" width="62" height="78" rx="8"/>
                  <rect x="430" y="28" width="174" height="72" rx="8"/>
                  <rect x="430" y="148" width="78" height="58" rx="8"/>
                  <rect x="524" y="148" width="80" height="58" rx="8"/>
                  <rect x="430" y="248" width="174" height="78" rx="8"/>
                </g>
                <g fill="none" stroke="#f7fbfa" stroke-width="16" stroke-linecap="round">
                  <path d="M0 124H640"/>
                  <path d="M0 228H640"/>
                  <path d="M188 0V360"/>
                  <path d="M400 0V360"/>
                </g>
                <g fill="none" stroke="#a9ccc4" stroke-width="5" stroke-linecap="round">
                  <path d="M188 124H400"/>
                  <path d="M188 228H400"/>
                  <path d="M294 124V228"/>
                </g>
              </svg>
              <span class="contact-map__pin">
                <svg viewBox="0 0 24 24"><path d="M12 21s7-5.2 7-10a7 7 0 10-14 0c0 4.8 7 10 7 10z"/><circle cx="12" cy="11" r="2.4"/></svg>
              </span>
            </div>
            <div class="contact-map__live" data-map-live>
              <div class="contact-map__switch" data-map-switch role="tablist" aria-label="Pinned locations"></div>
              <div class="contact-map__frame" data-map-canvas>
                <iframe data-map-frame title="Pinned location" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>
          <a class="contact-map__cta" data-map-open href="https://www.google.com/maps/search/?api=1&amp;query=Narayanganj%2C+Bangladesh" target="_blank" rel="noopener">
            View on Google Maps
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5h5v5"/><path d="M19 5l-9 9"/><path d="M17 13.5V19a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h5.5"/></svg>
            <span class="sr-only" data-map-note>Opens a district search until a pin is set.</span>
          </a>
        </div>

        <ul class="contact-lines">
          @if($site->telHref())
            <li>
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 4h3l1.2 3.2-1.8 1.1a12 12 0 006.3 6.3l1.1-1.8L20 14v3a2 2 0 01-2.2 2A15 15 0 015 6.2 2 2 0 017 4z"/></svg></div>
              <div><h3>Phone</h3><p><a href="{{ $site->telHref() }}">{{ $site->phone }}</a></p></div>
            </li>
          @endif
          @if(mill_filled($site->email))
            <li>
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M4 7l8 6 8-6"/></svg></div>
              <div><h3>Email</h3><p><a href="mailto:{{ $site->email }}">{{ $site->email }}</a></p></div>
            </li>
          @endif
          @if(mill_filled($site->website))
            <li>
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/></svg></div>
              <div><h3>Website</h3><p><a href="{{ str_starts_with($site->website, 'http') ? $site->website : 'https://'.$site->website }}">{{ $site->website }}</a></p></div>
            </li>
          @endif
          @if(mill_filled($site->hours))
            <li>
              <div class="contact-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 1.5"/></svg></div>
              <div><h3>Business hours</h3><p>{!! nl2br(e(str_replace(', ', "\n", $site->hours))) !!}</p></div>
            </li>
          @endif
        </ul>
      </aside>

      <form class="form quote-card" data-validate action="{{ route('quote.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="text" name="website_url" tabindex="-1" autocomplete="off" hidden>
        <header class="quote-card__head">
          <h2>Request a quote</h2>
          <p>Share the program details and the Dhaka desk will reply with next steps.</p>
        </header>
        @if($errors->any())<p class="error" style="display:block">{{ $errors->first() }}</p>@endif
        <div class="form-row form-row--2">
          <div class="field">
            <label for="name">Full name <span class="req">*</span></label>
            <input id="name" name="name" required autocomplete="name" placeholder="Your name" value="{{ old('name') }}">
            <p class="error">Name is required.</p>
          </div>
          <div class="field">
            <label for="company">Company <span class="req">*</span></label>
            <input id="company" name="company" required placeholder="Company name" value="{{ old('company') }}">
            <p class="error">Company is required.</p>
          </div>
        </div>
        <div class="form-row form-row--2">
          <div class="field">
            <label for="email">Email <span class="req">*</span></label>
            <input id="email" name="email" type="email" required autocomplete="email" placeholder="name@company.com" value="{{ old('email') }}">
            <p class="error">Valid email is required.</p>
          </div>
          <div class="field">
            <label for="phone">Phone <span class="req">*</span></label>
            <input id="phone" name="phone" required autocomplete="tel" placeholder="+880…" value="{{ old('phone') }}">
            <p class="error">Phone is required.</p>
          </div>
        </div>
        <div class="form-row form-row--2">
          <div class="field">
            <label for="country">Country</label>
            <input id="country" name="country" autocomplete="country-name" placeholder="Country" value="{{ old('country') }}">
          </div>
          <div class="field">
            <label for="interest">Product interest <span class="req">*</span></label>
            <select id="interest" name="interest" required>
              <option value="">Select…</option>
              @foreach(['Yarn / Spinning', 'Woven Fabric (Greige)', 'Finished / Piece-dyed Fabric', 'Custom program'] as $option)
                <option @selected(old('interest') === $option)>{{ $option }}</option>
              @endforeach
            </select>
            <p class="error">Please select a product interest.</p>
          </div>
        </div>
        <div class="field">
          <label for="message">Message / specifications <span class="req">*</span></label>
          <textarea id="message" name="message" required placeholder="Counts, constructions, finishes, quantity, target delivery…">{{ old('message') }}</textarea>
          <p class="error">Message is required.</p>
        </div>
        <div class="field">
          <span class="label" id="attach-label">Attachment <span class="req">optional</span></span>
          <label class="file-drop" data-max-mb="10" for="attach">
            <input id="attach" name="attachment" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" aria-labelledby="attach-label">
            <strong>Drop a file here</strong> or click to browse
            <span class="file-drop__name">PDF, Office, or image · max 10MB</span>
          </label>
        </div>
        <button type="submit" class="btn btn--primary">Submit inquiry</button>
      </form>
    </div>
  </section>

  <section class="contact-trust">
    <div class="container contact-trust__grid">
      <article class="contact-trust__item">
        <div class="contact-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 1.5"/></svg></div>
        <div><h2>Fast response</h2><p>The Dhaka desk replies within one business day.</p></div>
      </article>
      <article class="contact-trust__item">
        <div class="contact-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 12.5l3.2 3.2L18 7.5"/></svg></div>
        <div><h2>Sample support</h2><p>Swatches and lab dips for approved programs.</p></div>
      </article>
      <article class="contact-trust__item">
        <div class="contact-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg></div>
        <div><h2>Export ready</h2><p>Documents and packing held to buyer specification.</p></div>
      </article>
      <article class="contact-trust__item">
        <div class="contact-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20h16M6 20V9l6-5 6 5v11"/><path d="M10 20v-5h4v5"/></svg></div>
        <div><h2>Mill access</h2><p>Narayanganj visits by appointment for buyers and auditors.</p></div>
      </article>
    </div>
  </section>
@endsection
