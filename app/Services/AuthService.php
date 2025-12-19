<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthService
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function attemptLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.',
                'user' => null,
            ];
        }

        $user = Auth::user();

        // Kiểm tra email đã được xác thực chưa
        if (is_null($user->email_verified_at)) {
            Auth::logout();
            return [
                'success' => false,
                'unverified' => true,
                'message' => 'Vui lòng xác thực email trước khi đăng nhập. Kiểm tra hộp thư của bạn.',
                'user' => null,
                'unverified_email' => $user->email,
                'user_id' => $user->user_id,
            ];
        }

        return [
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'user' => $user,
        ];
    }

    public function registerUser(array $data): array
    {
        // Hash mật khẩu trước khi lưu
        $data['password'] = Hash::make($data['password']);

        try {
            // Chỉ lưu các cột được phép; phần còn lại để null; mặc định role là 'user'
            $user = User::create([
                'username' => $data['username'] ?? null,
                'email' => $data['email'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'full_name' => $data['full_name'] ?? null,
                'address' => $data['address'] ?? null,
                'password' => $data['password'],
                'role' => 'user',
            ]);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Đăng ký thất bại. ' . $e->getMessage(),
                'user' => null,
            ];
        }

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Đăng ký thất bại. Vui lòng thử lại.',
                'user' => null,
            ];
        }

        // Gửi email xác nhận
        $this->mailService->sendWelcomeEmail($user);

        return [
            'success' => true,
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận.',
            'user' => $user,
        ];
    }

    public function sendVerificationEmail(User $user): bool
    {
        return $this->mailService->sendWelcomeEmail($user);
    }

    /**
     * Gửi email reset password
     */
    public function sendPasswordResetEmail(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại.',
            ];
        }

        try {
            // Xóa token cũ chưa sử dụng
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            // Tạo token mới
            $token = Str::random(64);
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

            // Lưu token vào database
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Gửi email
            $this->mailService->sendPasswordResetEmail($user, $resetUrl, 15);

            return [
                'success' => true,
                'message' => 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi gửi email: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(array $data): array
    {
        $token = $data['token'];
        $email = $data['email'];
        $password = $data['password'];

        // Kiểm tra token có hợp lệ không
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return [
                'success' => false,
                'message' => 'Liên kết reset password không hợp lệ.',
            ];
        }

        // Kiểm tra token đã hết hạn (15 phút)
        $createdAt = strtotime($passwordReset->created_at);
        if (time() - $createdAt > 900) { // 900 giây = 15 phút
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $token)
                ->delete();

            return [
                'success' => false,
                'message' => 'Liên kết reset password đã hết hạn. Vui lòng yêu cầu lại.',
            ];
        }

        try {
            // Cập nhật mật khẩu
            $user = User::where('email', $email)->firstOrFail();
            $user->update([
                'password' => Hash::make($password),
            ]);

            // Xóa token đã sử dụng
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return [
                'success' => true,
                'message' => 'Mật khẩu đã được cập nhật thành công.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật mật khẩu: ' . $e->getMessage(),
            ];
        }
    }
}
