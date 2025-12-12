<div class="profile-page">
    <div class="profile-container">
        <h1>Edit Profile</h1>
        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form wire:submit.prevent='updateProfile'>
            <div class="form-grid">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" wire:model="firstname" placeholder="Enter your first name">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" wire:model="lastname" placeholder="Enter your last name">
                </div>
                <div class="form-group">
                    <label for="username">Username @error('username')
                        <span style="color: red">{{ '(' }}{{ $message }}{{ ')' }}</span>
                    @enderror
                    </label>
                    <input type="text" id="username" wire:model="username" placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <label for="email">Email @error('email')
                        <span style="color: red">{{ '(' }}{{ $message }}{{ ')' }}</span>
                    @enderror
                    </label>
                    <input type="email" id="email" wire:model="email" placeholder="Enter your email">
                </div>
            </div>
            <button type="submit" class="btn-submit" disabled wire:dirty.remove.attr='disabled'>Save
                Changes</button>
        </form>
        <div class="form-grid" style="margin-top: 24px;">
            <div class="form-group" style="grid-column: 1 / -1;">
                <h2>Two-Factor Authentication</h2>
                <p>Protect your account with an authenticator app. You will need a one-time code at login.</p>
                <a href="/two-factor" class="btn-submit" style="display: inline-block; text-align: center;">Manage
                    2FA</a>
            </div>
        </div>
    </div>
</div>