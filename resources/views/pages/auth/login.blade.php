@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid login-container">
            <h2>Đăng Nhập</h2>
            <x-form-alerts></x-form-alerts>
            
            <form method="POST" action="{{ route('login_handle') }}">
                @csrf
                <div class="mb-1 mt-2">
                    <label for="login_id" class="form-label">Tài khoản</label>
                    <input type="text" class="form-control" id="login_id" name="login_id" placeholder="Nhập Email hoặc Username">
                </div>
                @error('login_id')
                    <span class="text-danger ml-1">{{ $message }}</span>                
                @enderror
                <div class="mb-1 mt-2">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
                </div>
                @error('password')
                    <span class="text-danger ml-1">{{ $message }}</span>                
                @enderror
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                </div>
                <div class="form-text">
                    <a href="{{ route('password.forgot') }}">Quên mật khẩu?</a>
                </div>
                <div class="register-text">
                    <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection
