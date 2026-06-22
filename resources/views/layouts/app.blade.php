<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERTA - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">
</head>
<body>
    <div class="d-flex">
        @include('layouts.sidebar')

        <div class="dashboard-container">
            <div class="dashboard-header">
                <h1 class="title">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo SIPERTA"
                         style="height:40px; vertical-align:middle; margin-right:10px;"
                         onerror="this.style.display='none'">
                    SIPERTA
                </h1>
                <a href="{{ Route::has(auth()->user()->role . '.profile') ? route(auth()->user()->role . '.profile') : '#' }}" class="profile">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ auth()->user()->name ?? 'Profile' }}</span>
                </a>
            </div>

            <div class="dashboard-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
