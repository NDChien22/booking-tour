<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\ForgotPasswordEmail;

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
        if (Mail::to($user->email)->send(new WelcomeEmail($user))) {
            return true;
        } else {
            return false;
        }
    }

}
