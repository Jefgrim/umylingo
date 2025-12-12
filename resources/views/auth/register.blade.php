<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Signup - UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/logo.png">
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
</head>

<body>
    <!-- navbar -->
    <div class="container auth-layout" style="gap: 24px;">
        <div class="auth-brand" style="flex:0 0 180px; display:flex; align-items:center; justify-content:center;">
            <a href="/"><img src="assets/img/logo.png" alt="UMYLINGO" style="max-width: 160px; height:auto;"></a>
        </div>
        <div class="form-container" style="width: 100%; max-width: 560px; margin: 0 auto;">
            <form method="POST" action="/register" class="login-form" style="width:100%; max-width: 100%;">
                @csrf
                
                    <h2>Sign up</h2>
                    <div class="signup-input-container" style="width: 100%; max-width: 100%;">
                        <input type="text" placeholder="First Name" name="firstname" value="{{ old('firstname') }}"
                            required style="max-width: 100%;">
                        <input type="text" placeholder="Last Name" name="lastname" value="{{ old('lastname') }}"
                            required style="max-width: 100%;">
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        @error('username')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                        <input type="text" placeholder="Username" name="username" value="{{ old('username') }}"
                            required style="width: 100%; max-width: 100%;">

                        @error('email')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                        <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required style="width: 100%; max-width: 100%;">
                        @error('password')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="signup-input-container" style="width: 100%; max-width: 100%;">
                        <input type="password" placeholder="Password" name="password" required style="max-width: 100%;">
                        <input type="password" placeholder="Confirm Password" name="password_confirmation" required style="max-width: 100%;">
                    </div>
                    <button style="width: 100%; max-width: 100%;">Register</button>
                    <p>Already Have an Account? </p><a href="/login" style="color: black">Log in</a>
                
            </form>
        </div>
    </div>
    <!-- footer -->
</body>

</html>