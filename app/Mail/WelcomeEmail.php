<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: 'Chào mừng bạn đến với TCTravel!',
        );
    }

    public function content(): Content
    {
        // Tạo signed URL có thời hạn 15 phút
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(15),
            ['user_id' => $this->user->user_id, 'hash' => sha1($this->user->email)]
        );

        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'appName' => config('app.name'),
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
