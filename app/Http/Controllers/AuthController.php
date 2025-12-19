<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authService;
    protected $userRepository;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function loginForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Đăng nhập',
        ];

        return view('pages.auth.login', $data);
    }

    public function registerForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Đăng ký',
        ];

        return view('pages.auth.register', $data);
    }


    public function loginHandle(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        $result = $this->authService->attemptLogin($credentials);

        if ($result['success']) {
            return redirect()->intended(route('dashboard'));
        }

        // Nếu lỗi do chưa xác thực email, lưu thông tin để hiển thị nút gửi lại
        if (isset($result['unverified_email'])) {
            return redirect()->route('login')
                ->with('unverified_email', $result['unverified_email'])
                ->with('user_id', $result['user_id']);
        }

        return redirect()->route('login')->with('error', $result['message'] ?? 'Đăng nhập thất bại');
    }

    public function registerHandle(RegisterRequest $request)
    {
        $data = $request->validated();

        $result = $this->authService->registerUser($data);

        if ($result['success']) {
            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
        }

        return redirect()->route('register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }

    /**
     * Xác thực email của người dùng
     */
    public function verifyEmail(Request $request, $user_id)
    {
        // Tìm user theo ID
        $user = User::findOrFail($user_id);

        // Kiểm tra hash email có khớp không
        if (!hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return redirect()->route('login')->with('error', 'Link xác thực không hợp lệ.');
        }

        // Kiểm tra email đã được xác thực chưa
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email đã được xác thực trước đó.');
        }

        // Cập nhật trực tiếp vào database
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Email đã được xác thực thành công! Bạn có thể đăng nhập.');
    }

    /**
     * Gửi lại email xác thực (cho guest - chưa đăng nhập)
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Gửi lại email
        $this->authService->sendVerificationEmail($user);

        return redirect()->route('login')->with('success', 'Link xác thực mới đã được gửi đến email của bạn!');
    }

    public function forgotPasswordForm()
    {
        $data = [
            'pageTitle' => 'Quên Mật Khẩu',
        ];

        return view('pages.auth.forgot-password', $data);
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $result = $this->authService->sendPasswordResetEmail($request->email);

        if ($result['success']) {
            return redirect()->route('login')->with('success', 'Liên kết reset password đã được gửi đến email của bạn. Vui lòng kiểm tra email.');
        }

        return redirect()->route('password.forgot')->with('error', $result['message'] ?? 'Gửi email thất bại.');
    }

    public function resetPasswordForm(Request $request, $token)
    {
        $email = $request->email;

        // Kiểm tra token có hợp lệ và chưa hết hạn
        if (!$this->validateResetToken($token, $email)) {
            return redirect()->route('password.forgot')->with('error', 'Liên kết đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu lại.');
        }

        $data = [
            'pageTitle' => 'Đặt Lại Mật Khẩu',
            'token' => $token,
            'email' => $email,
        ];

        return view('pages.auth.reset-password', $data);
    }

    private function validateResetToken($token, $email): bool
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return false;
        }

        // Kiểm tra token đã hết hạn (15 phút)
        $createdAt = strtotime($passwordReset->created_at);
        if (time() - $createdAt > 900) { // 900 giây = 15 phút
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $token)
                ->delete();
            return false;
        }

        return true;
    }

    public function resetPasswordHandle(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:5|confirmed',
        ],[
            'password.min'=>'Mật khẩu phải có ít nhất 5 ký tự.',
            'password.confirmed'=>'Mật khẩu xác nhận không khớp.',
        ]);

        $result = $this->authService->resetPassword($data);

        if ($result['success']) {
            return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập.');
        }

        return redirect()->back()->withErrors(['email' => $result['message'] ?? 'Đặt lại mật khẩu thất bại.']);
    }
}
