<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Two-Factor Challenge - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <style>
        .challenge-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 1rem;
        }

        .challenge-card {
            background-color: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .challenge-icon {
            text-align: center;
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .challenge-title {
            color: #0c5894;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
        }

        .challenge-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .success-message {
            background-color: #d1e7dd;
            border: 1px solid #a3cfbb;
            color: #0f5132;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-label {
            color: #0c5894;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .challenge-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1.1rem;
            text-align: center;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.3rem;
            transition: border-color 0.3s;
        }

        .challenge-input:focus {
            outline: none;
            border-color: #0c5894;
            box-shadow: 0 0 0 3px rgba(12, 88, 148, 0.1);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #ddd;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background-color: #fdfbfb;
            padding: 0 1rem;
            color: #999;
            font-weight: 600;
        }

        .btn-verify {
            background-color: #0c5894;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-verify:hover {
            background-color: #094270;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .help-text {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            color: #004085;
            text-align: center;
            font-size: 0.9rem;
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

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .challenge-container {
                margin: 1rem auto;
            }

            .challenge-card {
                padding: 1.5rem;
            }

            .challenge-input {
                font-size: 1rem;
                letter-spacing: 0.2rem;
            }
        }
    </style>
</head>

<body>
    <div style="text-align: center; padding: 1rem;">
        <a href="/"><img src="/assets/img/logo.png" alt="UMYLINGO" style="max-width: 120px;"></a>
    </div>
    <div class="container">

        <div class="challenge-container">
            <form method="POST" action="/two-factor-challenge">
                @csrf
                <div class="challenge-card">
                    <div class="challenge-icon">🔐</div>
                    <h1 class="challenge-title">Verification Required</h1>
                    <p class="challenge-subtitle">Enter the code from your authenticator app to continue</p>

                    @if (session('status'))
                        <div class="success-message">{{ session('status') }}</div>
                    @endif
                    @error('code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('recovery_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <div class="input-group">
                        <label class="input-label">📱 Authenticator Code</label>
                        <input type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*"
                            placeholder="000000" name="code" value="{{ old('code') }}" class="challenge-input"
                            maxlength="6">
                    </div>

                    <div class="divider">
                        <span>OR</span>
                    </div>

                    <div class="input-group">
                        <label class="input-label">🔑 Recovery Code</label>
                        <input type="text" placeholder="Enter recovery code" name="recovery_code"
                            value="{{ old('recovery_code') }}" class="challenge-input" style="letter-spacing: normal;">
                    </div>

                    <button type="submit" class="btn-verify">✓ Verify & Continue</button>

                    <div class="help-text">
                        <strong>💡 Tip:</strong> If you've lost access to your authenticator app, use one of your
                        recovery codes instead.
                    </div>
                </div>
            </form>

            <div class="back-link">
                <a href="/login">← Back to Login</a>
            </div>
        </div>
    </div>
</body>

</html>