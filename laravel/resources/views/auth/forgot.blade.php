<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset password | Islam Textile</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-page">
  <main class="login-stage">
    <form class="login-card" method="post" action="{{ route('password.email') }}">
      @csrf
      <h1>Reset password</h1>
      <p class="login-lead">We will email a reset link to the admin address on file.</p>
      @if(session('status'))<p class="login-note">{{ session('status') }}</p>@endif
      <div class="field">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
      </div>
      <button class="btn btn-primary" type="submit">Send reset link</button>
      <p class="login-note"><a href="{{ route('login') }}">Back to sign in</a></p>
    </form>
  </main>
</body>
</html>
