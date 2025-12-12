<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Two-Factor Settings - UMYLINGO</title>
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
        <div class="form-container" style="max-width: 720px;">
            <div class="login-input-container">
                <h2>Two-Factor Authentication</h2>
                @if (session('status'))
                    <p style="color: #0f5132">{{ session('status') }}</p>
                @endif

                @if (!$hasConfirmedTwoFactor)
                    <p>Step 1: Scan the QR code with your authenticator app.</p>
                    <div style="text-align: center; margin: 16px 0;">
                        <div id="qrcode"></div>
                    </div>
                    <p style="text-align: center;">Or manually enter this secret: <strong>{{ $secret }}</strong></p>
                    <p>Use Google Authenticator, Authy, or any compatible TOTP app.</p>

                    <form method="POST" action="/two-factor/confirm" style="margin-top: 16px;">
                        @csrf
                        @error('code')
                            <p style="color: #ad3324">{{ $message }}</p>
                        @enderror
                        <input type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*"
                            placeholder="Enter the 6-digit code" name="code" required>
                        <button>Verify & Enable</button>
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
                    <p>Two-factor authentication is currently enabled.</p>

                    <div style="margin-top: 16px;">
                        <h4>Recovery Codes</h4>
                        <p>Store these codes in a safe place. Each code can be used once.</p>
                        <ul style="list-style: none; padding-left: 0;">
                            @foreach ($recoveryCodes as $code)
                                <li style="font-family: monospace; margin-bottom: 4px;">{{ $code }}</li>
                            @endforeach
                        </ul>
                        <form method="POST" action="/two-factor/recovery-codes" style="margin-top: 8px;">
                            @csrf
                            <button type="submit">Regenerate Recovery Codes</button>
                        </form>
                    </div>

                    <form method="POST" action="/two-factor" style="margin-top: 16px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #ad3324; color: white;">Disable Two-Factor</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
