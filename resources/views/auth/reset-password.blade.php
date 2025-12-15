<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Reset Password - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .reset-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .reset-card {
            background-color: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .reset-title {
            color: #ad3324;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .section-title {
            color: #0c5894;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
        }
        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            word-wrap: break-word;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #0c5894;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            color: #094270;
            word-wrap: break-word;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            color: #856404;
            word-wrap: break-word;
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
            max-width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-input:focus {
            outline: none;
            border-color: #0c5894;
        }
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
            margin-top: 1rem;
            box-sizing: border-box;
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
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: #999;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .reset-container {
                padding: 0.5rem;
            }
            .reset-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div style="text-align: center; padding: 1rem;">
        <a href="/"><img src="/assets/img/logo.png" alt="UMYLINGO" style="max-width: 150px;"></a>
    </div>

    <div class="reset-container">
        <div class="reset-card">
            <h1 class="reset-title">Reset Password</h1>
            
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="info-box">
                <strong>Username:</strong> {{ $username }}
            </div>

            <form method="POST" action="{{ route('password.reset') }}">
                @csrf
                <input type="hidden" name="username" value="{{ $username }}">

                @if ($has2FA)
                    <h3 class="section-title">Recovery Code Verification</h3>
                    <div class="warning-box">
                        Enter one of your recovery codes to verify your identity. The code will be consumed after use.
                    </div>
                    <div class="form-group">
                        <label for="recovery_code" class="form-label">Recovery Code</label>
                        <input type="text" id="recovery_code" name="recovery_code" class="form-input" 
                               placeholder="Enter recovery code" autocomplete="off">
                    </div>
                @endif

                @if ($has2FA && $hasSecurityQuestions)
                    <div class="divider">- OR -</div>
                @endif

                @if ($hasSecurityQuestions)
                    <h3 class="section-title">Security Questions</h3>
                    <div class="info-box">
                        Answer your security questions to verify your identity.
                    </div>
                    @foreach ($questions as $question)
                        <div class="form-group">
                            <label for="answer_{{ $question->id }}" class="form-label">{{ $question->question }}</label>
                            <input type="text" id="answer_{{ $question->id }}" name="answer_{{ $question->id }}" 
                                   class="form-input" placeholder="Enter your answer" autocomplete="off">
                        </div>
                    @endforeach
                @endif

                <h3 class="section-title">New Password</h3>
                <div class="info-box">
                    Password must be at least 12 characters and contain letters and symbols.
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Enter new password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="form-input" placeholder="Confirm new password" required>
                </div>

                <button type="submit" class="btn-primary">Reset Password</button>
            </form>

            <div class="back-link">
                <a href="{{ route('password.request') }}">← Back</a>
            </div>
        </div>
    </div>
</body>

</html>
