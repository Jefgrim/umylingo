<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMYLINGO</title>
    <link rel="icon" type="image/png" sizes="500x500" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="500x500" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <div class="brand-container">
            <a href="/app/decks"><img src="{{ asset('assets/img/logo.png') }}" alt="logo"></a>
        </div>

        <div class="menu-container">
            <form class="profile-icon" method="POST" action="/logout">
                @csrf
                <button><svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path
                            d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 224c0 17.7 14.3 32 32 32s32-14.3 32-32l0-224zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </header>
    <div class="main-container">
        <div class="sidebar">
            @can('administrate')
                <a href="/app/dashboard" class="active">Dashboard</a>
            @endcan
            <a href="/app/decks">Decks</a>
            <a href="/app/notes">Notes</a>
            <a href="/app/progress">Progress</a>
            <a href="/app/achievements">Achievements</a>
            <a href="/app/profile">Profile</a>
        </div>
        <div class="main">
            {{ $slot }}
        </div>
    </div>
</body>
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
    integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</html>
