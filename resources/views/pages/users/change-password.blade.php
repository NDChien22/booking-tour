@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid profile-container">
            <h2>Đổi mật khẩu</h2>
            <x-form-alerts></x-form-alerts>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                @error('current_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="new_password" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="password_confirmation"
                        name="password_confirmation" required>
                </div>
                @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    <a href="{{ route('profile') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
