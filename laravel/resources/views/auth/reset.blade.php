<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose a password | Islam Textile</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-page">
  <main class="login-stage">
    <form class="login-card" method="post" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <h1>Choose a password</h1>
      <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', $email) }}" required></div>
      <div class="field"><label for="password">New password</label><input id="password" name="password" type="password" required></div>
      <div class="field"><label for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" required></div>
      @error('email')<p class="login-note">{{ $message }}</p>@enderror
      <button class="btn btn-primary" type="submit">Update password</button>
    </form>
  </main>
</body>
</html>
