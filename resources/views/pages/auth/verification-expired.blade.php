@extends('layout.pages-layout')
@section('pageTitle', 'Xác thực email - Link hết hạn')
@push('stylesheets')
    <style>
        .verify-expired-container {
            max-width: 640px;
            margin: 40px auto;
        }

        .expired-card {
            background: #fff;
            border-radius: 8px;
            padding: 28px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
        }

        .expired-icon {
            font-size: 56px;
            line-height: 1;
        }

        .muted {
            color: #6c757d;
        }
    </style>
@endpush
@section('content')
    <div class="content d-flex flex-column">
        <div class="container-fluid verify-expired-container">
            <div class="expired-card">
                <div class="text-center mb-3">
                    <div class="expired-icon">⏰</div>
                    <h3 class="mt-2">Link xác thực đã hết hạn</h3>
                    <p class="muted mb-0">Liên kết bạn bấm đã quá thời hạn 15 phút</p>
                </div>

                <x-form-alerts></x-form-alerts>

                @if (!empty($user_id))
                    <form method="POST" action="{{ route('verification.resend') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">📧 Gửi lại email xác thực</button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">← Quay về đăng nhập</a>
                        </div>
                    </form>
                @else
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">← Quay về đăng nhập</a>
                    </div>
                @endif

                <hr class="my-4">
                <p class="small text-muted mb-0">Mẹo: Sau khi nhận email mới, hãy bấm xác thực trong vòng 15 phút để hoàn
                    tất.</p>
            </div>
        </div>
    </div>
@endsection
