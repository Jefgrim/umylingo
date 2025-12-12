<div class="profile-wrapper">
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-icon">❓</div>
            <h1>Security Questions</h1>
            <p class="profile-subtitle">Configure security questions for password recovery</p>
        </div>

        <div class="profile-section">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-error">
                    <span class="alert-icon">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(auth()->user()->securityQuestions->count() > 0)
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <span class="alert-icon">✓</span>
                    <span>You have {{ auth()->user()->securityQuestions->count() }} security question(s) configured.</span>
                </div>
            @else
                <div class="alert alert-warning" style="margin-bottom: 1.5rem; background-color: #fff3cd; border-color: #ffc107; color: #856404;">
                    <span class="alert-icon">⚠</span>
                    <span>No security questions configured. Add at least one to enable password recovery.</span>
                </div>
            @endif

            <form wire:submit.prevent="save">
                @foreach([0, 1, 2] as $index)
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="label-text" style="display: block; color: #0c5894; font-weight: 600; margin-bottom: 0.5rem;">
                            <span class="label-icon">{{ $index + 1 }}.</span>
                            Security Question {{ $index + 1 }}
                        </label>
                        <select wire:model="questions.{{ $index }}" style="width: 100%; max-width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 0.5rem; font-size: 1rem; box-sizing: border-box;">
                            <option value="">-- Select a question --</option>
                            @foreach($availableQuestions as $q)
                                <option value="{{ $q }}">{{ $q }}</option>
                            @endforeach
                        </select>
                        
                        @if(!empty($questions[$index] ?? ''))
                            <input type="text" wire:model="answers.{{ $index }}" placeholder="Your answer (case-insensitive)" 
                                   style="width: 100%; max-width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; box-sizing: border-box;">
                        @endif
                    </div>
                @endforeach

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <span class="btn-icon">💾</span>
                        Save Security Questions
                    </button>
                </div>
            </form>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/profile" style="color: #0c5894; text-decoration: none; font-weight: 600;">← Back to Profile</a>
        </div>
    </div>
</div>
</div>

