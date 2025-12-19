<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Kiểm tra đã xác thực chưa
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email đã được xác thực. Vui lòng đăng nhập.');
        }

        // Gửi lại email
        $this->authService->sendVerificationEmail($user);

        return redirect()->route('login')->with('success', 'Link xác thực mới đã được gửi đến email của bạn!');
    }
}
