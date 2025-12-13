<x-layouts.admin title="Verify 2FA for {{ $intendedPage ?? 'Logs' }}">
    <div class="logs-verify">
        <div class="verify-card">
            <h1>Verify Two-Factor to View {{ $intendedPage ?? 'Logs' }}</h1>
            <p class="subtitle">Enter an authenticator code or a recovery code to continue.</p>

            @if(session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.logs.verify') }}" class="verify-form">
                @csrf
                <div class="form-group">
                    <label for="code">Authenticator Code</label>
                    <input type="text" id="code" name="code" inputmode="numeric" pattern="[0-9]*" placeholder="6-digit code">
                    @error('code')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="divider">or</div>

                <div class="form-group">
                    <label for="recovery_code">Recovery Code</label>
                    <input type="text" id="recovery_code" name="recovery_code" placeholder="Recovery code">
                    @error('recovery_code')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <button class="btn-submit">Verify & Continue</button>
            </form>
        </div>
    </div>

    <style>
        .logs-verify {
            display: flex;
            justify-content: center;
            padding: 2rem;
        }
        .verify-card {
            background: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 2rem;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
        }
        .verify-card h1 {
            font-size: 1.5rem;
            color: #0c5894;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #555;
            margin-bottom: 1.5rem;
        }
        .alert-info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0b4f82;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .verify-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .form-group input {
            padding: 0.85rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0c5894;
            box-shadow: 0 0 0 3px rgba(12, 88, 148, 0.1);
        }
        .divider {
            text-align: center;
            color: #888;
            font-weight: 600;
        }
        .btn-submit {
            padding: 0.9rem 1.25rem;
            background: #0c5894;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-submit:hover {
            background: #094270;
            transform: translateY(-1px);
        }
        .error {
            color: #ad3324;
            font-size: 0.9rem;
        }
    </style>
</x-layouts.admin>
