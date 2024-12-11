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
    <div class="container">
        <div>
            <a href="/"><img src="assets/img/logo.png" alt=""></a>
        </div>
        <div class="form-container">
            <form method="POST" action="/register" class="login-form">
                @csrf
                
                    <h2>Sign up</h2>
                    <div class="signup-input-container">
                        <input type="text" placeholder="First Name" name="firstname" value="{{ old('firstname') }}"
                            required>
                        <input type="text" placeholder="Last Name" name="lastname" value="{{ old('lastname') }}"
                            required>
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                        @error('username')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                        <input type="text" placeholder="Username" name="username" value="{{ old('username') }}"
                            required>

                        @error('email')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                        <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                        @error('password')
                        <p style="color: #ad3324; margin: 0; font-size: 10px; text-align: start; width: 70%;">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="signup-input-container">
                        <input type="password" placeholder="Password" name="password" required>
                        <input type="password" placeholder="Confirm Password" name="password_confirmation" required>
                    </div>
                    <button>Register</button>
                    <p>Already Have an Account? </p><a href="/login" style="color: black">Log in</a>
                
            </form>
        </div>
    </div>
    <!-- footer -->
</body>

</html>