<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Two-Factor Challenge - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
</head>

<body>
    <div class="container">
        <div>
            <a href="/"><img src="/assets/img/logo.png" alt=""></a>
        </div>
        <div class="form-container">
            <form method="POST" action="/two-factor-challenge" class="login-form">
                @csrf
                <div class="login-input-container">
                    <h2>Two-Factor Verification</h2>
                    @if (session('status'))
                        <p style="color: #0f5132">{{ session('status') }}</p>
                    @endif
                    @error('code')
                        <p style="color: #ad3324">{{ $message }}</p>
                    @enderror
                    @error('recovery_code')
                        <p style="color: #ad3324">{{ $message }}</p>
                    @enderror
                    <input type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*"
                        placeholder="6-digit code" name="code" value="{{ old('code') }}">
                    <p style="text-align:center; margin: 8px 0;">or</p>
                    <input type="text" placeholder="Recovery code" name="recovery_code"
                        value="{{ old('recovery_code') }}">
                    <button>Verify</button>
                    <p>If you lost access to your authenticator, use a recovery code.</p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
