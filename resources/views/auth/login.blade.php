<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; }
        .auth-container {
            max-width: 520px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .auth-card {
            background-color: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .auth-title {
            color: #ad3324;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            color: #0c5894;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            max-width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-input:focus { outline: none; border-color: #0c5894; }
        .btn-primary {
            background-color: #0c5894;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            max-width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover { background-color: #094270; }
        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 0.85rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }
        .link-row { text-align: center; margin-top: 1rem; }
        .link-row a { color: #0c5894; font-weight: 600; text-decoration: none; }
        .link-row a:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .auth-container { padding: 0.5rem; }
            .auth-card { padding: 1.5rem; }
        }
    </style>
</head>

<body>
    <div style="text-align: center; padding: 1rem;">
        <a href="/"><img src="/assets/img/logo.png" alt="UMYLINGO" style="max-width: 150px;"></a>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">🔐 Login</h1>

            @if (session('status'))
                <div class="error-message" style="background-color:#d1e7dd; border-color:#a3cfbb; color:#0f5132;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Login</button>
            </form>

            <div class="link-row" style="margin-top: 1rem;">
                <a href="/forgot-password">Forgot Password?</a>
            </div>
            <div class="link-row" style="margin-top: 0.5rem;">
                <span>Don't have an account? </span><a href="/register">Sign up</a>
            </div>
        </div>
    </div>
</body>

</html>
