<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $resetUrl;
    public string $deepLink;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $resetUrl, string $deepLink)
    {
        $this->userName = $userName;
        $this->resetUrl = $resetUrl;
        $this->deepLink = $deepLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Acadova Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
        );
    }
}
