@extends('layouts.site', ['metaTitle' => 'Careers | '.$site->site_name, 'metaDescription' => $page?->lead])
@section('content')
  <section class="page-hero" style="background:linear-gradient(90deg,rgba(0,61,51,.92),rgba(0,61,51,.55)),url('{{ mill_url('media/products/product-0.jpg') }}') center/cover">
    <div class="container page-hero__content">
      <nav class="breadcrumb page-hero__crumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span> <span>Careers</span>
      </nav>
      <h1>{{ $page?->title ?: 'Careers' }}</h1>
      <p>Build lasting skills in a focused cotton textile mill — spinning, weaving, finishing, and quality in Bangladesh.</p>
      <div class="hero-values" style="margin-top:2rem">
        <div class="hero-values__item"><h3>Craft</h3><p>Hands-on mill expertise</p></div>
        <div class="hero-values__item"><h3>Integrity</h3><p>Honest specs &amp; delivery</p></div>
        <div class="hero-values__item"><h3>Growth</h3><p>Skills across the process</p></div>
        <div class="hero-values__item"><h3>Safety</h3><p>People &amp; plant first</p></div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <header class="section__head reveal">
        <p class="section__eyebrow">Open Roles</p>
        <h2 class="section__title">Join the Mill Team</h2>
        <p class="section__lead">{{ $page?->lead ?: 'Positions based in Dhaka and Narayanganj, Bangladesh.' }}</p>
      </header>
      <div class="jobs-table-wrap reveal">
        <table class="jobs-table">
          <thead>
            <tr><th>Role</th><th>Department</th><th>Location</th><th>Type</th><th></th></tr>
          </thead>
          <tbody>
            @foreach($jobs as $job)
              <tr>
                <td>
                  <div class="job-role__title">{{ $job->title }}</div>
                  @if(mill_filled($job->summary))<div class="job-role__dept">{{ $job->summary }}</div>@endif
                </td>
                <td>{{ $job->department }}</td>
                <td>{{ $job->location }}</td>
                <td>{{ $job->employment_type }}</td>
                <td><a class="btn btn--outline" href="{{ route('careers.show', $job->slug) }}">Apply</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section class="section section--surface">
    <div class="container">
      <header class="section__head reveal">
        <p class="section__eyebrow">How We Work</p>
        <h2 class="section__title">Principles on the Floor</h2>
      </header>
      <div class="values-row reveal">
        <div>
          <div class="values-row__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8 12l2.5 2.5L16 9"/></svg></div>
          <h3>Quality First</h3>
          <p>Every lot checked against construction and shade standards.</p>
        </div>
        <div>
          <div class="values-row__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 19V5h4v14H4zm6 0V9h4v10h-4zm6 0v-6h4v6h-4z"/></svg></div>
          <h3>Continuous Skill</h3>
          <p>Cross-training across spinning, weaving, and finishing.</p>
        </div>
        <div>
          <div class="values-row__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3l8 4v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V7l8-4z"/></svg></div>
          <h3>Safe Campus</h3>
          <p>PPE, machine guards, and clear shift protocols.</p>
        </div>
        <div>
          <div class="values-row__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.5-7-10a4 4 0 018 0 4 4 0 018 0c0 5.5-7 10-7 10z"/></svg></div>
          <h3>Respect</h3>
          <p>Fair schedules and clear communication with leadership.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-band reveal">
    <div class="container">
      <h2>Don’t See Your Role?</h2>
      <p>Send a resume for general consideration. Our team reviews applications for Dhaka and Narayanganj openings.</p>
      <button type="button" class="btn btn--primary" data-modal-open="resume-modal">Submit Resume</button>
    </div>
  </section>

  <div id="resume-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="resume-modal-title">
    <div class="modal__dialog">
      <button type="button" class="modal__close" aria-label="Close">&times;</button>
      <h2 id="resume-modal-title">Submit Resume</h2>
      <p>General application for Islam Textile careers in Bangladesh.</p>
      <form class="form" data-validate action="#" method="post">
        <div class="field">
          <label for="resume-name">Full name</label>
          <input id="resume-name" name="name" required autocomplete="name">
          <p class="error">Name is required.</p>
        </div>
        <div class="field">
          <label for="resume-email">Email</label>
          <input id="resume-email" name="email" type="email" required autocomplete="email">
          <p class="error">Valid email is required.</p>
        </div>
        <div class="field">
          <label for="resume-phone">Phone</label>
          <input id="resume-phone" name="phone" required autocomplete="tel" placeholder="+880…">
          <p class="error">Phone is required.</p>
        </div>
        <div class="field">
          <label for="resume-note">Role interest / note</label>
          <textarea id="resume-note" name="message" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn--primary">Send Application</button>
      </form>
    </div>
  </div>
@endsection
