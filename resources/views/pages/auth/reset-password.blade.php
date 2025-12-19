@extends('layout.pages-layout')
@section('pageTitle', 'Đặt lại mật khẩu')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/forgot_password.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid login-container">
            <h2>Đặt Lại Mật Khẩu</h2>
            <x-form-alerts></x-form-alerts>
            <form method="POST" action="{{ route('password_reset') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-1 mt-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $email }}"
                        readonly>
                </div>
                <div class="mb-1 mt-2">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-1 mt-2">
                    <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        required>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
@endsection
