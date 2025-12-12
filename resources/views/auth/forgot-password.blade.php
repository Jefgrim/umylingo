<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
        .forgot-container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 2rem;
        }
        .forgot-card {
            background-color: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .forgot-title {
            color: #ad3324;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .info-text {
            color: #666;
            line-height: 1.6;
            margin: 1rem 0;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            color: #0c5894;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #0c5894;
        }
        .btn-primary {
            background-color: #0c5894;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #094270;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #0c5894;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div style="text-align: center; padding: 1rem;">
        <a href="/"><img src="/assets/img/logo.png" alt="UMYLINGO" style="max-width: 150px;"></a>
    </div>

    <div class="forgot-container">
        <div class="forgot-card">
            <h1 class="forgot-title">🔑 Forgot Password</h1>
            
            @if (session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <p class="info-text">Enter your username to continue with password reset.</p>

            <form method="GET" action="{{ route('password.reset.form') }}">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input" 
                           placeholder="Enter your username" required autofocus>
                </div>

                <button type="submit" class="btn-primary">Continue →</button>
            </form>

            <div class="back-link">
                <a href="/login">← Back to Login</a>
            </div>
        </div>
    </div>
</body>

</html>
