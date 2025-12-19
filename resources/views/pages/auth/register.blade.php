@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid register-container">
            <h2>Đăng Ký</h2>
            <x-form-alerts></x-form-alerts>
            <form method="POST" action="{{ route('register_handle') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Nhập tên đăng nhập" value="{{ old('username') }}" required>
                </div>
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email"
                        value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                        placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}" required>
                </div>
                @error('phone_number')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu"
                        required>
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                </div>
                <div class="form-text">
                    Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
@endsection
