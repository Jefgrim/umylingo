<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/logo.png">
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
</head>

<body>
    <!-- navbar -->
    <div class="container">
        <div>
            <a href="/"><img src="assets/img/logo.png" alt=""></a>
        </div>
        <div class="form-container">
            <form method="POST" action="/login" class="login-form">
                @csrf
                <div class="login-input-container">
                    <h2>Login</h2>
                    @if (session('status'))
                        <p style="color: #0f5132; background-color: #d1e7dd; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">{{ session('status') }}</p>
                    @endif
                    @error('username')
                        <p style="color: #ad3324">{{ $message }}</p>
                    @enderror
                    @error('password')
                        <p style="color: #ad3324">{{ $message }}</p>
                    @enderror
                    <input type="text" placeholder="Username" name="username" value="{{ old('username') }}" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <button>Login</button>
                    <p style="margin-top: 1rem;"><a href="/forgot-password" style="color: #0c5894; text-decoration: underline;">Forgot Password?</a></p>
                    <p>Don't Have an Account? </p><a href="/register" style="color: black">Sign up</a>
                </div>
            </form>
        </div>
    </div>
    <!-- footer -->
</body>

</html>
