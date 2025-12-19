@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid profile-container">
            <h2>Thông Tin Người Dùng</h2>
            <x-form-alerts></x-form-alerts>
            <form method="POST" action="{{ route('profile_update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                        value="{{ old('phone_number', $user->phone_number) }}" required>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="full_name" name="full_name"
                        value="{{ old('full_name', $user->full_name) }}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ old('address', $user->address) }}">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="{{ route('password.change') }}" class="btn btn-secondary">Đổi mật khẩu</a>
                </div>
            </form>
        </div>
    </div>
@endsection
