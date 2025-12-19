<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Gửi email chào mừng đến user mới
     * 
     * @param User $user
     * @return bool
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi email reset password
     * 
     * @param User $user
     * @param string $resetUrl
     * @param int $expiresMinutes
     * @return bool
     */
    public function sendPasswordResetEmail(User $user, string $resetUrl, int $expiresMinutes = 60): bool
    {
        try {
            Mail::to($user->email)->send(new ForgotPasswordEmail($user, $resetUrl, $expiresMinutes));
            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return false;
        }
    }
}
