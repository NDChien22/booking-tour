<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('stylesheets')
</head>

<body class="p-1">
    <!-- Header -->
    <header class="sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg bg-white border-bottom">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">TCTravel</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                aria-current="page" href="{{ route('dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.history') }}">Lịch sử đặt tour</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Liên hệ hỗ trợ</a>
                        </li>
                    </ul>

                    @auth
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('profile') }}"
                                class="d-inline-flex align-items-center gap-2 text-decoration-none">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle bg-primary text-white"
                                    style="width:32px;height:32px;font-size:0.9rem;">
                                    {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                                </span>
                                <span class="fw-semibold text-dark">{{ auth()->user()->username }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                            </form>
                        </div>
                    @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Content -->
    @yield('content')
    <footer class="bg-black text-center text-light">
        <p class="mb-0 p-3">Copyright © 2022 My Website. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
