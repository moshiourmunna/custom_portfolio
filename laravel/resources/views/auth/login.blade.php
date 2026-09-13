<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Islam Textile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-page">
  <div class="login-watermark" aria-hidden="true"></div>
  <main class="login-stage">
    <form class="login-card" method="post" action="{{ route('login') }}">
      @csrf
      <div class="login-brand">
        <span class="login-brand__mark" aria-hidden="true"></span>
        <span class="login-brand__word">
          <strong>Islam Textile</strong>
          <small>Weaving Tradition, Ensuring Quality</small>
        </span>
      </div>
      <h1>Admin Login</h1>
      <p class="login-lead">Sign in to access the CMS dashboard</p>
      @if(session('status'))<p class="login-note">{{ session('status') }}</p>@endif
      <div class="field">
        <label for="email">Email address</label>
        <div class="login-input">
          <input id="email" name="email" type="email" value="{{ old('email', 'admin@islamtextile.com') }}" required autocomplete="username">
        </div>
        @error('email')<p class="error" style="display:block">{{ $message }}</p>@enderror
      </div>
      <div class="field">
        <label for="password">Password</label>
        <div class="login-input">
          <input id="password" name="password" type="password" required autocomplete="current-password">
          <button class="login-eye" type="button" data-toggle-password aria-label="Show password">Show</button>
        </div>
      </div>
      <div class="login-row">
        <label class="login-check"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <a class="login-forgot" href="{{ route('password.request') }}">Forgot password?</a>
      </div>
      <button class="btn btn-primary btn--labeled" type="submit">Sign in</button>
      <p class="login-note"><a href="{{ route('home') }}">View the public site</a></p>
    </form>
  </main>
  <script>
    document.querySelector('[data-toggle-password]')?.addEventListener('click', function () {
      var input = document.getElementById('password');
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  </script>
</body>
</html>
