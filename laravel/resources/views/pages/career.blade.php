@extends('layouts.site', ['metaTitle' => $job->title.' | Careers', 'metaDescription' => $job->summary])
@section('content')
  <section class="page-hero">
    <div class="container page-hero__content">
      <nav class="breadcrumb page-hero__crumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">/</span>
        <a href="{{ route('careers') }}">Careers</a> <span aria-hidden="true">/</span>
        <span>{{ $job->title }}</span>
      </nav>
      <h1>{{ $job->title }}</h1>
      <p>{{ $job->location }} · {{ $job->employment_type }} · {{ $job->department }} Department</p>
    </div>
  </section>

  <section class="section">
    <div class="container contact-grid">
      <div>
        <p class="section__eyebrow">Role Overview</p>
        <h2 class="section__title">{{ $job->summary ?: $job->title }}</h2>
        @if(mill_filled($job->description))<p>{{ $job->description }}</p>@endif
        @if($job->slug === 'quality-inspector-woven-fabric')
          <h3 style="margin-top:1.5rem;color:var(--color-primary)">Responsibilities</h3>
          <ul>
            <li>Perform visual and dimensional checks on greige and finished fabric</li>
            <li>Record construction, shade, and handfeel findings against buyer specs</li>
            <li>Escalate non-conformances and follow corrective actions</li>
            <li>Coordinate with weaving and finishing supervisors on lot status</li>
          </ul>
          <h3 style="margin-top:1.5rem;color:var(--color-primary)">Requirements</h3>
          <ul>
            <li>Experience in woven fabric QC preferred</li>
            <li>Familiarity with count, construction, and shade evaluation</li>
            <li>Clear documentation and communication skills in Bangla and English</li>
            <li>Willingness to work mill shifts at Narayanganj</li>
          </ul>
        @endif
      </div>
      <div class="panel">
        <h2 class="section__title" style="font-size:1.35rem;margin-top:0">Apply for this role</h2>
        @if($errors->any())<p class="error" style="display:block">{{ $errors->first() }}</p>@endif
        <form class="form" data-validate action="{{ route('careers.apply', $job->slug) }}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="text" name="website_url" tabindex="-1" autocomplete="off" hidden>
          <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" required autocomplete="name" value="{{ old('name') }}">
            <p class="error">Name is required.</p>
          </div>
          <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}">
            <p class="error">Valid email is required.</p>
          </div>
          <div class="field">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" required autocomplete="tel" placeholder="+880…" value="{{ old('phone') }}">
            <p class="error">Phone is required.</p>
          </div>
          <div class="field">
            <label for="cv">CV / resume</label>
            <div class="file-drop" data-max-mb="5">
              <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx">
              <p>Drop PDF or Word file here, or click to browse</p>
              <p class="file-drop__name">No file selected</p>
            </div>
          </div>
          <div class="field">
            <label for="message">Cover note</label>
            <textarea id="message" name="message" rows="4">{{ old('message') }}</textarea>
          </div>
          <button type="submit" class="btn btn--primary btn--block">Submit application</button>
        </form>
      </div>
    </div>
  </section>
@endsection
