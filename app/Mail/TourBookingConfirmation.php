<?php 
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TourBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public $tour, public $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: 'Xác nhận đặt tour thành công!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tour-booking-confirmation',
            with: [
                'user' => $this->user,
                'tour' => $this->tour,
                'booking' => $this->booking,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
?> 