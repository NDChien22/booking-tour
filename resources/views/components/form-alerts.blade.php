<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('unverified_email'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>⚠️ Email chưa được xác thực!</strong>
            <p class="mb-2">Bạn cần xác thực email trước khi đăng nhập.</p>
            <p class="mb-2">Email: <strong>{{ session('unverified_email') }}</strong></p>
            <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                @csrf
                <input type="hidden" name="user_id" value="{{ session('user_id') }}">
                <button type="submit" class="btn btn-sm btn-warning">
                    📧 Gửi lại email xác thực
                </button>
            </form>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
