<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Two-Factor Settings - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="/assets/img/logo.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <style>
        .two-factor-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .two-factor-card {
            background-color: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .two-factor-title {
            color: #ad3324;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .two-factor-subtitle {
            color: #0c5894;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
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
        }
        .qr-code-section {
            background-color: white;
            border: 2px solid #0c5894;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin: 1.5rem 0;
        }
        #qrcode {
            display: inline-block;
            padding: 1rem;
            background: white;
            border-radius: 8px;
        }
        .secret-code {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            color: #0c5894;
            font-weight: bold;
            margin: 1rem 0;
            word-break: break-all;
        }
        .info-text {
            color: #666;
            line-height: 1.6;
            margin: 1rem 0;
        }
        .step-badge {
            background-color: #0c5894;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .recovery-codes-box {
            background-color: #fff9e6;
            border: 2px solid #ffd700;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .recovery-codes-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }
        .recovery-code-item {
            background-color: white;
            border: 1px solid #ddd;
            padding: 0.75rem;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            text-align: center;
            color: #333;
        }
        .two-factor-form {
            margin-top: 1.5rem;
        }
        .two-factor-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1rem;
            transition: border-color 0.3s;
        }
        .two-factor-input:focus {
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
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #ad3324;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }
        .btn-danger:hover {
            background-color: #8a2719;
        }
        .status-badge {
            display: inline-block;
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            color: #856404;
        }
        @media (max-width: 768px) {
            .two-factor-container {
                padding: 1rem;
            }
            .two-factor-card {
                padding: 1.5rem;
            }
            .recovery-codes-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
     <div style="text-align: center; padding: 1rem;">
            <a href="/"><img src="/assets/img/logo.png" alt="UMYLINGO" style="max-width: 150px;"></a>
        </div>
    <div class="container">
       
        <div class="two-factor-container">
            <div class="two-factor-card">
                <h1 class="two-factor-title">🔐 Two-Factor Authentication</h1>
                
                @if (session('status'))
                    <div class="success-message">{{ session('status') }}</div>
                @endif

                @if (!$hasConfirmedTwoFactor)
                    <div class="info-text">
                        <p><span class="step-badge">1</span>Scan the QR code below with your authenticator app</p>
                    </div>
                    
                    <div class="qr-code-section">
                        <div id="qrcode"></div>
                    </div>
                    
                    <div class="info-text" style="text-align: center;">
                        <p>Or manually enter this secret code:</p>
                        <div class="secret-code">{{ $secret }}</div>
                    </div>
                    
                    <div class="warning-box">
                        <strong>📱 Recommended Apps:</strong> Google Authenticator, Microsoft Authenticator, Authy, or any compatible TOTP app
                    </div>

                    <form method="POST" action="/two-factor/confirm" class="two-factor-form">
                        @csrf
                        <div class="info-text">
                            <p><span class="step-badge">2</span>Enter the 6-digit code from your authenticator app</p>
                        </div>
                        @error('code')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <input type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*"
                            placeholder="Enter 6-digit code" name="code" required class="two-factor-input" maxlength="6">
                        <button type="submit" class="btn-primary">✓ Verify & Enable 2FA</button>
                    </form>
                    
                    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            new QRCode(document.getElementById('qrcode'), {
                                text: {!! json_encode($qrCodeUrl) !!},
                                width: 256,
                                height: 256,
                                colorDark : "#000000",
                                colorLight : "#ffffff",
                                correctLevel : QRCode.CorrectLevel.H
                            });
                        });
                    </script>
                @else
                    <div style="text-align: center;">
                        <span class="status-badge">✓ Two-Factor Authentication is Active</span>
                    </div>

                    @if($showRecoveryCodes)
                        <div class="warning-box" style="background-color: #dc3545; border-color: #dc3545; color: white; padding: 1.5rem; margin: 1.5rem 0;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.2rem;">⚠️ CRITICAL: Save These Codes NOW!</h3>
                            <p style="margin: 0; font-size: 1rem; line-height: 1.6;"><strong>These recovery codes will ONLY be shown this ONE TIME and cannot be retrieved later.</strong> Save them in a secure password manager or write them down and store them safely. You will need one of these codes if you lose access to your authenticator app.</p>
                        </div>
                        
                        <div class="recovery-codes-box">
                            <h3 class="two-factor-subtitle" style="margin-top: 0;">🔑 Your Recovery Codes (One-Time Display)</h3>
                            <ul class="recovery-codes-list">
                                @foreach ($recoveryCodes as $code)
                                    <li class="recovery-code-item">{{ $code }}</li>
                                @endforeach
                            </ul>
                            <p class="info-text" style="text-align: center; margin-top: 1rem;"><strong>Each code can only be used once.</strong> Make sure you've saved all codes before leaving this page.</p>
                        </div>
                    @else
                        <div class="warning-box" style="border: 2px solid #ffc107; padding: 1.5rem;">
                            <h3 style="color: #856404; margin-top: 0; font-size: 1.1rem;">🔑 Recovery Codes</h3>
                            <p style="color: #856404; margin-bottom: 0.5rem;"><strong>Your recovery codes are securely stored.</strong> They were shown once when you first enabled 2FA and cannot be viewed again for security reasons.</p>
                            <p style="color: #856404; margin-bottom: 0;"><strong>Important:</strong> If you've lost access to your recovery codes, you will need to disable and re-enable 2FA to get new codes. Keep your codes in a secure location.</p>
                        </div>
                    @endif

                    <div class="two-factor-card" style="border: 2px solid #ad3324;">
                        <h3 class="two-factor-subtitle" style="color: #ad3324;">⚠️ Disable Two-Factor Authentication</h3>
                        <p class="info-text">To disable 2FA, enter a current verification code from your authenticator app or use one of your recovery codes.</p>
                        <form method="POST" action="/two-factor" class="two-factor-form">
                            @csrf
                            @method('DELETE')
                            @error('code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <input type="text" autocomplete="one-time-code"
                                placeholder="Enter 6-digit code or recovery code" name="code" required class="two-factor-input">
                            <button type="submit" class="btn-danger">🔓 Disable Two-Factor Authentication</button>
                        </form>
                    </div>
                @endif
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                @if(auth()->user()->isAdmin)
                    <a href="/dashboard/profile" style="color: #0c5894; text-decoration: none; font-weight: 600;">← Back to Profile</a>
                @else
                    <a href="/profile" style="color: #0c5894; text-decoration: none; font-weight: 600;">← Back to Profile</a>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
