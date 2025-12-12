<div class="profile-wrapper">
    <div class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-icon">🛡️</div>
                <h1>Admin Profile</h1>
                <p class="profile-subtitle">Manage your admin account details and security</p>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="profile-section">
                <h2 class="section-title">
                    <span class="section-icon">📝</span>
                    Personal Information
                </h2>
                <form wire:submit.prevent='updateProfile'>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first-name">
                                <span class="label-icon">👤</span>
                                First Name
                            </label>
                            <input type="text" id="first-name" wire:model="firstname" placeholder="Enter your first name">
                        </div>
                        <div class="form-group">
                            <label for="last-name">
                                <span class="label-icon">👤</span>
                                Last Name
                            </label>
                            <input type="text" id="last-name" wire:model="lastname" placeholder="Enter your last name">
                        </div>
                        <div class="form-group">
                            <label for="username">
                                <span class="label-icon">@</span>
                                Username
                            </label>
                            <input type="text" id="username" wire:model="username" placeholder="Enter your username">
                            @error('username')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">
                                <span class="label-icon">✉</span>
                                Email
                            </label>
                            <input type="email" id="email" wire:model="email" placeholder="Enter your email">
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit" disabled wire:dirty.remove.attr='disabled'>
                            <span class="btn-icon">💾</span>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-section password-section">
                <h2 class="section-title">
                    <span class="section-icon">🔑</span>
                    Password & Security
                </h2>
                <form wire:submit.prevent="updatePassword">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current-password">
                                <span class="label-icon">🔒</span>
                                Current Password
                            </label>
                            <input type="password" id="current-password" wire:model="current_password" placeholder="Enter current password">
                            @error('current_password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new-password">
                                <span class="label-icon">✨</span>
                                New Password
                            </label>
                            <input type="password" id="new-password" wire:model="password" placeholder="Enter new password">
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">
                                <span class="label-icon">✅</span>
                                Confirm New Password
                            </label>
                            <input type="password" id="confirm-password" wire:model="password_confirmation" placeholder="Confirm new password">
                            @error('password_confirmation')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if(auth()->user()?->two_factor_confirmed_at)
                        <div class="twofactor-hint">
                            2FA is enabled. Enter an authenticator code <strong>or</strong> a recovery code to change your password.
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="twofactor-code">
                                    <span class="label-icon">📱</span>
                                    Authenticator Code
                                </label>
                                <input type="text" id="twofactor-code" wire:model="two_factor_code" inputmode="numeric" pattern="[0-9]*" placeholder="6-digit code">
                                @error('two_factor_code')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="recovery-code">
                                    <span class="label-icon">🔑</span>
                                    Recovery Code
                                </label>
                                <input type="text" id="recovery-code" wire:model="recovery_code" placeholder="Recovery code">
                                @error('recovery_code')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="submit" class="btn-submit" disabled wire:dirty.remove.attr='disabled'>
                            <span class="btn-icon">🔄</span>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <div class="profile-section security-section">
                <h2 class="section-title">
                    <span class="section-icon">🔐</span>
                    Security Settings
                </h2>
                <div class="security-card">
                    <div class="security-content">
                        <h3>Two-Factor Authentication</h3>
                        <p>Admin accounts are strongly encouraged to enable 2FA. You will need a one-time code from your authenticator app when signing in.</p>
                        @if(auth()->user()->two_factor_confirmed_at)
                            <div class="status-badge active">
                                <span class="badge-icon">✓</span>
                                2FA Enabled
                            </div>
                        @else
                            <div class="status-badge inactive">
                                <span class="badge-icon">○</span>
                                2FA Disabled
                            </div>
                        @endif
                    </div>
                    <div class="security-action">
                        <a href="/two-factor" class="btn-secondary">
                            <span class="btn-icon">⚙</span>
                            Manage 2FA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e5e5e5;
        }

        .profile-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .profile-page .profile-container h1 {
            margin-bottom: 0.5rem;
            font-size: 2rem;
            color: #ad3324;
        }

        .profile-subtitle {
            color: #666;
            font-size: 1rem;
            margin: 0;
        }

        .alert.alert-success {
            background-color: #d1e7dd;
            border: 1px solid #a3cfbb;
            color: #0f5132;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-icon {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .password-section {
            border: 2px solid #f1f5f9;
        }

        .twofactor-hint {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0b4f82;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin: 1rem 0 1.5rem;
            font-size: 0.95rem;
        }

        .profile-section {
            margin-bottom: 2rem;
            background-color: white;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .section-title {
            color: #0c5894;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-icon {
            font-size: 1.5rem;
        }

        .label-icon {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .profile-page .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .profile-page .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }

        .profile-page .form-group input {
            padding: 0.875rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .profile-page .form-group input:focus {
            border-color: #0c5894;
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 88, 148, 0.1);
        }

        .error-text {
            color: #ad3324;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-actions {
            margin-top: 1.5rem;
        }

        .profile-page .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1rem;
            background-color: #ad3324;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-page .btn-submit:disabled {
            background-color: #999;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .profile-page .btn-submit:not(:disabled):hover {
            background-color: #8a2719;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(173, 51, 36, 0.3);
        }

        .btn-icon {
            font-size: 1.2rem;
        }

        .security-section {
            border: 2px solid #0c5894;
        }

        .security-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .security-content {
            flex: 1;
            min-width: 250px;
        }

        .security-content h3 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .security-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-badge.active {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
        }

        .status-badge.inactive {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f1aeb5;
        }

        .badge-icon {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .security-action {
            flex-shrink: 0;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background-color: #0c5894;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #094270;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(12, 88, 148, 0.3);
        }

        @media (max-width: 768px) {
            .profile-icon {
                font-size: 3rem;
            }

            .profile-page .profile-container h1 {
                font-size: 1.6rem;
            }

            .profile-subtitle {
                font-size: 0.9rem;
            }

            .profile-page .form-grid {
                grid-template-columns: 1fr;
            }

            .security-card {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 480px) {
            .profile-page {
                padding: 10px;
            }

            .profile-page .profile-container {
                padding: 20px;
            }

            .profile-icon {
                font-size: 2.5rem;
            }

            .section-icon {
                font-size: 1.2rem;
            }
        }
    </style>
</div>