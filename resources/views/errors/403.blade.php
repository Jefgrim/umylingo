<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>403 Forbidden</title>
    <link rel="icon" type="image/png" sizes="500x500" href="{{asset('assets/img/logo.png')}}">
    <link rel="icon" type="image/png" sizes="500x500" href="{{asset('assets/img/logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
</head>

<body>
    <!-- navbar -->
    <div class="container">
        <div>
            <img src="{{asset('assets/img/logo.png')}}" alt="">
        </div>
        <div style="display: flex; flex-direction: column; align-items:center">
            <h1 style="color: #ad3324">403 Forbidden</h1>
            <h3 style="color: #0c5894">{{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}</h3>
            <a href="{{ url('/decks') }}" class="btn btn-primary" style="padding: 20px; border-radius: 10px; color: #fdfbfb; background-color:#0c5894; text-decoration: none;">Return</a>
        </div>
    </div>
    <!-- footer -->
</body>

</html>