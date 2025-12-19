<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                'user_id' => $user->id,
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
}
