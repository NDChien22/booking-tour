@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/forgot_password.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid login-container">
            <h2>Quên Mật Khẩu</h2>
            <x-form-alerts></x-form-alerts>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-1 mt-2">
                    <label for="user" class="form-label">Vui lòng nhập địa chỉ email để nhận liên kết đặt lại mật
                        khẩu</label>
                    <div class="input-group custom">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8V4z"/>
                                <path d="M0 6.383v5.617A2 2 0 0 0 2 14h12a2 2 0 0 0 2-2V6.383l-7.555 4.533a1 1 0 0 1-1.018 0L0 6.383z"/>
                            </svg>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" autocomplete="email" required>
                    </div>
                </div>
                @error('email')
                    <span class="text-danger ml-1">{{ $message }}</span>
                @enderror
                <div class="d-grid gap-2 mb-1 mt-2">
                    <button type="submit" class="btn btn-primary btn-block">Gửi Liên Kết</button>
                </div>
                <div class="d-grid gap-2 mt-2">
                    <a href="{{ route('login') }}"><button type="button"
                            class="btn btn-outline-primary btn-lg btn-block">Đăng Nhập</button></a>
                </div>
            </form>
        </div>
    </div>
@endsection
